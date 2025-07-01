<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Employee;
use App\Models\EmployeeAttendence;
use App\Models\Workman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class EmployeeAttendenceController extends Controller
{
    /**
     * Display the attendance page with employee list and form.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $email = Auth::user()->email;

        // Log access to attendance page
        Log::info('User accessed attendance page', [
            'user_email' => $email,
            'ip_address' => $request->ip(),
        ]);

        // Find the authenticated workman
        $workman = Workman::where('email', $email)->first();

        if (!$workman) {
            Log::error('Workman not found', [
                'email' => $email,
                'ip_address' => $request->ip(),
            ]);

            ActivityLog::create([
                'action' => 'Error: Workman Not Found',
                'details' => "No workman found for email {$email}",
                'user' => 'HR',
            ]);
            abort(404, 'Workman not found.');
        }

        ActivityLog::create([
            'action' => 'Accessed Attendance Page',
            'details' => "User {$email} accessed attendance page",
            'user' => 'HR',
        ]);

        // Get request parameters
        $date = $request->input('date', Carbon::today()->toDateString());
        $search = $request->input('search', '');

        Log::info('Attendance search initiated', [
            'user_email' => $email,
            'date' => $date,
            'search_term' => $search ?: 'none',
        ]);

        ActivityLog::create([
            'action' => 'Attendance Search',
            'details' => "Searched attendance for date {$date}" . ($search ? " with search term: {$search}" : ""),
            'user' => 'HR',
        ]);

        // Query employees with attendance for the month
        $monthStart = Carbon::parse($date)->startOfMonth()->toDateString();
        $monthEnd = Carbon::parse($date)->endOfMonth()->toDateString();

        $workmenQuery = Employee::with(['location', 'attendances' => function ($query) use ($monthStart, $monthEnd) {
            $query->whereBetween('attendance_date', [$monthStart, $monthEnd]);
        }])
            ->where('location_id', $workman->location_id)
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('surname', 'like', "%{$search}%")
                        ->orWhere('employee_unquee_id', 'like', "%{$search}%")
                        ->orWhere('clim_id', 'like', "%{$search}%");
                });
            });

        $workmen = $workmenQuery->paginate(10);

        // Transform attendance data for Sunday checkboxes
        $workmen->getCollection()->transform(function ($workman) {
            $workman->sunday_attendance = $workman->attendances
                ->where('status', 'present')
                ->pluck('status', 'attendance_date')
                ->toArray();
            return $workman;
        });

        // Check if attendance exists for the selected date
        $attendanceExists = EmployeeAttendence::where('attendance_date', $date)->exists();

        Log::info('Checked attendance existence', [
            'user_email' => $email,
            'date' => $date,
            'exists' => $attendanceExists,
        ]);

        if ($attendanceExists) {
            ActivityLog::create([
                'action' => 'Attendance Check',
                'details' => "Attendance already submitted for date {$date}",
                'user' => 'HR',
            ]);
        }

        return view('HRAdmin.employeeAttendence', compact('workmen', 'date', 'search', 'attendanceExists'));
    }

    /**
     * Store attendance records, including overtime and Sunday attendance.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $userEmail = Auth::user()->email;

        Log::info('Attendance submission started', [
            'user_email' => $userEmail,
            'input_data' => $request->all(),
            'ip_address' => $request->ip(),
        ]);

        try {
            // Validate request data
            $validated = $request->validate([
                'attendance_date' => 'required|date',
                'attendance' => 'required|array',
                'attendance.*.employee_id' => 'required|exists:employees,id',
                'attendance.*.location_id' => 'required|exists:locations,id',
                'attendance.*.status' => 'required|in:present,absent,leave',
                'attendance.*.overtime_hours' => 'nullable|numeric|min:0|max:500',
                'attendance.*.notes' => 'nullable|string|max:1000',
                'attendance.*.sunday_attendance' => 'nullable|array',
                'attendance.*.sunday_attendance.*' => 'nullable|in:present',
            ]);

            $attendanceDate = $validated['attendance_date'];
            $nextDay = Carbon::parse($attendanceDate)->addDay()->toDateString();
            $savedRecords = 0;

            Log::info('Attendance submission validated', [
                'user_email' => $userEmail,
                'attendance_date' => $attendanceDate,
                'record_count' => count($validated['attendance']),
            ]);

            ActivityLog::create([
                'action' => 'Attempted Attendance Submission',
                'details' => "User {$userEmail} attempted to submit attendance for {$attendanceDate} with " . count($validated['attendance']) . " records",
                'user' => 'HR',
            ]);

            // Get all Sundays in the month
            $monthStart = Carbon::parse($attendanceDate)->startOfMonth();
            $monthEnd = Carbon::parse($attendanceDate)->endOfMonth();
            $sundayDates = [];
            for ($day = $monthStart; $day <= $monthEnd; $day->addDay()) {
                if ($day->isSunday()) {
                    $sundayDates[] = $day->toDateString();
                }
            }

            // Check existing records for selected date, next day, and Sundays
            $existingRecords = EmployeeAttendence::whereIn('attendance_date', array_merge([$attendanceDate, $nextDay], $sundayDates))
                ->select('employee_id', 'attendance_date')
                ->get()
                ->groupBy('attendance_date')
                ->map(function ($group) {
                    return $group->pluck('employee_id')->toArray();
                })
                ->toArray();

            Log::info('Checked existing attendance records', [
                'user_email' => $userEmail,
                'dates' => array_merge([$attendanceDate, $nextDay], $sundayDates),
                'existing_records' => $existingRecords,
            ]);

            foreach ($validated['attendance'] as $index => $attendanceData) {
                $employeeId = $attendanceData['employee_id'];
                $recordsSavedForEmployee = false;

                Log::debug('Processing attendance for employee', [
                    'user_email' => $userEmail,
                    'employee_id' => $employeeId,
                    'index' => $index,
                    'attendance_data' => $attendanceData,
                ]);

                try {
                    // Create main attendance record if it doesn't exist
                    if (!isset($existingRecords[$attendanceDate]) || !in_array($employeeId, $existingRecords[$attendanceDate] ?? [])) {
                        $record = EmployeeAttendence::create([
                            'employee_id' => $employeeId,
                            'location_id' => $attendanceData['location_id'],
                            'attendance_date' => $attendanceDate,
                            'status' => $attendanceData['status'],
                            'overtime_hours' => null, // Overtime stored separately
                            'notes' => $attendanceData['notes'] ?? null,
                        ]);

                        $recordsSavedForEmployee = true;

                        Log::info('Main attendance record created', [
                            'user_email' => $userEmail,
                            'employee_id' => $employeeId,
                            'date' => $attendanceDate,
                            'status' => $attendanceData['status'],
                            'notes' => $attendanceData['notes'] ?? 'none',
                            'record_id' => $record->id,
                        ]);

                        ActivityLog::create([
                            'action' => 'Attendance Record Created',
                            'details' => "Attendance recorded for employee ID {$employeeId} on {$attendanceDate} with status {$attendanceData['status']}" .
                                ($attendanceData['notes'] ? " with notes: {$attendanceData['notes']}" : ""),
                            'user' => 'HR',
                        ]);
                    } else {
                        Log::info('Skipped existing main attendance record', [
                            'user_email' => $userEmail,
                            'employee_id' => $employeeId,
                            'date' => $attendanceDate,
                        ]);
                    }

                    // Create overtime record for the next day if applicable
                    if (!empty($attendanceData['overtime_hours']) && $attendanceData['overtime_hours'] > 0) {
                        if (!isset($existingRecords[$nextDay]) || !in_array($employeeId, $existingRecords[$nextDay] ?? [])) {
                            $overtimeRecord = EmployeeAttendence::create([
                                'employee_id' => $employeeId,
                                'location_id' => $attendanceData['location_id'],
                                'attendance_date' => $nextDay,
                                'status' => 'present',
                                'overtime_hours' => $attendanceData['overtime_hours'],
                                'notes' => $attendanceData['notes'] ?? null,
                            ]);

                            $recordsSavedForEmployee = true;

                            Log::info('Overtime record created', [
                                'user_email' => $userEmail,
                                'employee_id' => $employeeId,
                                'date' => $nextDay,
                                'overtime_hours' => $attendanceData['overtime_hours'],
                                'record_id' => $overtimeRecord->id,
                            ]);

                            ActivityLog::create([
                                'action' => 'Overtime Record Created',
                                'details' => "Overtime recorded for employee ID {$employeeId} on {$nextDay} with {$attendanceData['overtime_hours']} hours",
                                'user' => 'HR',
                            ]);
                        } else {
                            Log::info('Skipped existing overtime record', [
                                'user_email' => $userEmail,
                                'employee_id' => $employeeId,
                                'date' => $nextDay,
                            ]);
                        }
                    }

                    // Handle Sunday attendance
                    if (!empty($attendanceData['sunday_attendance'])) {
                        foreach ($attendanceData['sunday_attendance'] as $sundayDate => $status) {
                            if ($status === 'present' && (!isset($existingRecords[$sundayDate]) || !in_array($employeeId, $existingRecords[$sundayDate] ?? []))) {
                                $sundayRecord = EmployeeAttendence::create([
                                    'employee_id' => $employeeId,
                                    'location_id' => $attendanceData['location_id'],
                                    'attendance_date' => $sundayDate,
                                    'status' => 'present',
                                    'overtime_hours' => null,
                                    'notes' => $attendanceData['notes'] ?? null,
                                ]);

                                $recordsSavedForEmployee = true;

                                Log::info('Sunday attendance record created', [
                                    'user_email' => $userEmail,
                                    'employee_id' => $employeeId,
                                    'date' => $sundayDate,
                                    'status' => 'present',
                                    'record_id' => $sundayRecord->id,
                                ]);

                                ActivityLog::create([
                                    'action' => 'Sunday Attendance Record Created',
                                    'details' => "Sunday attendance recorded for employee ID {$employeeId} on {$sundayDate}",
                                    'user' => 'HR',
                                ]);
                            } else {
                                Log::info('Skipped existing Sunday attendance record', [
                                    'user_email' => $userEmail,
                                    'employee_id' => $employeeId,
                                    'date' => $sundayDate,
                                ]);
                            }
                        }
                    }

                    // Increment saved records if any record was created for this employee
                    if ($recordsSavedForEmployee) {
                        $savedRecords++;
                    }
                } catch (QueryException $e) {
                    Log::error('Failed to save attendance record', [
                        'user_email' => $userEmail,
                        'employee_id' => $employeeId,
                        'date' => $attendanceDate,
                        'error_message' => $e->getMessage(),
                        'record_index' => $index,
                    ]);

                    ActivityLog::create([
                        'action' => 'Error: Attendance Record Creation Failed',
                        'details' => "Failed to record attendance for employee ID {$employeeId} on {$attendanceDate}: {$e->getMessage()}",
                        'user' => 'HR',
                    ]);
                }
            }

            if ($savedRecords === 0) {
                Log::error('No attendance records saved', [
                    'user_email' => $userEmail,
                    'date' => $attendanceDate,
                    'attempted_records' => count($validated['attendance']),
                    'existing_records' => $existingRecords,
                ]);

                ActivityLog::create([
                    'action' => 'Error: No Attendance Records Saved',
                    'details' => "No attendance records saved for {$attendanceDate} by {$userEmail}. Attempted: " . count($validated['attendance']) . " records",
                    'user' => 'HR',
                ]);

                return redirect()->route('EmployeeAttendence')
                    ->with('error', 'No new attendance records were saved. All selected employees may already have attendance recorded.');
            }

            Log::info('Attendance submission completed', [
                'user_email' => $userEmail,
                'date' => $attendanceDate,
                'saved_record_count' => $savedRecords,
            ]);

            ActivityLog::create([
                'action' => 'Attendance Submitted',
                'user' => 'HR',
                'details' => "User {$userEmail} successfully submitted {$savedRecords} attendance records for {$attendanceDate}",
            ]);

            return redirect()->route('EmployeeAttendence')
                ->with('success', "Successfully submitted {$savedRecords} attendance records!");
        } catch (ValidationException $e) {
            Log::error('Attendance submission validation failed', [
                'user_email' => $userEmail,
                'errors' => $e->errors(),
                'input_data' => $request->all(),
            ]);

            ActivityLog::create([
                'action' => 'Error: Validation Failed',
                'details' => "Attendance submission failed for {$userEmail}: " . json_encode($e->errors()),
                'user' => 'HR',
            ]);

            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected error during attendance submission', [
                'user_email' => $userEmail,
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            ActivityLog::create([
                'action' => 'Error: Attendance Submission Failed',
                'details' => "Unexpected error for {$userEmail}: {$e->getMessage()}",
                'user' => 'HR',
            ]);

            return redirect()->route('EmployeeAttendence')
                ->with('error', 'An unexpected error occurred. Please try again.');
        }
    }
}
