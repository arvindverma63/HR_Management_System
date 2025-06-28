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

class EmployeeAttendenceController extends Controller
{
    public function index(Request $request)
    {
        $email = Auth::user()->email;

        Log::info('User accessed attendance page', [
            'user_email' => $email,
            'ip_address' => $request->ip(),
        ]);

        $workman = Workman::where('email', $email)->first();

        ActivityLog::create([
            'action' => 'Accessed Attendance Page',
            'details' => "User {$email} accessed attendance page",
            'user' => 'HR',
        ]);

        if (!$workman) {
            Log::error('Workman not found for email', [
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

        // Log table names for debugging
        Log::info('Model table names', [
            'workman_table' => (new Workman)->getTable(),
            'employee_table' => (new Employee)->getTable(),
        ]);

        $date = $request->input('date', now()->toDateString());
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

        $workmenQuery = Employee::with('location')
            ->where('location_id', $workman->location_id)
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('surname', 'like', "%{$search}%");
                });
            });

        $workmen = $workmenQuery->paginate(10);

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
     * Store the attendance records.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $userEmail = Auth::user()->email;

        Log::info('Attendance submission raw input', [
            'user_email' => $userEmail,
            'input_data' => $request->all(),
            'ip_address' => $request->ip(),
        ]);

        try {
            $validated = $request->validate([
                'attendance_date' => 'required|date',
                'attendance' => 'required|array',
                'attendance.*.employee_id' => 'required|exists:employees,id',
                'attendance.*.location_id' => 'required|exists:locations,id',
                'attendance.*.status' => 'required|in:present,absent,leave',
                'attendance.*.overtime_hours' => 'nullable|numeric|min:0|max:500',
                'attendance.*.notes' => 'nullable|string|max:1000',
            ]);

            Log::info('Attendance submission validated successfully', [
                'user_email' => $userEmail,
                'attendance_date' => $validated['attendance_date'],
                'record_count' => count($validated['attendance']),
            ]);

            $attendanceDate = $validated['attendance_date'];

            ActivityLog::create([
                'action' => 'Attempted Attendance Submission',
                'details' => "User {$userEmail} attempted to submit attendance for {$attendanceDate}",
                'user' => 'HR',
            ]);

            // Check for existing records for specific employees on this date
            $existingRecords = EmployeeAttendence::where('attendance_date', $attendanceDate)
                ->pluck('employee_id')
                ->toArray();
            Log::info('Checked for existing attendance records', [
                'user_email' => $userEmail,
                'date' => $attendanceDate,
                'existing_employee_ids' => $existingRecords,
            ]);

            if (!empty($existingRecords)) {
                Log::warning('Duplicate attendance submission attempt for some employees', [
                    'user_email' => $userEmail,
                    'date' => $attendanceDate,
                    'existing_employee_ids' => $existingRecords,
                ]);

                ActivityLog::create([
                    'action' => 'Warning: Duplicate Attendance',
                    'details' => "Attendance already submitted for {$attendanceDate} for employee IDs: " . implode(', ', $existingRecords),
                    'user' => 'HR',
                ]);
                // Allow partial submission by skipping existing records
            }

            $savedRecords = 0;
            foreach ($validated['attendance'] as $index => $attendanceData) {
                // Skip if record already exists for this employee and date
                if (in_array($attendanceData['employee_id'], $existingRecords)) {
                    Log::info('Skipped existing attendance record', [
                        'user_email' => $userEmail,
                        'employee_id' => $attendanceData['employee_id'],
                        'date' => $attendanceDate,
                    ]);
                    continue;
                }

                try {
                    $record = EmployeeAttendence::create([
                        'employee_id' => $attendanceData['employee_id'],
                        'location_id' => $attendanceData['location_id'],
                        'attendance_date' => $attendanceDate,
                        'status' => $attendanceData['status'],
                        'overtime_hours' => $attendanceData['overtime_hours'] ?? null,
                        'notes' => $attendanceData['notes'] ?? null,
                    ]);

                    $savedRecords++;

                    Log::info('Attendance record created', [
                        'user_email' => $userEmail,
                        'employee_id' => $attendanceData['employee_id'],
                        'date' => $attendanceDate,
                        'status' => $attendanceData['status'],
                        'overtime_hours' => $attendanceData['overtime_hours'] ?? 'none',
                        'notes' => $attendanceData['notes'] ?? 'none',
                        'record_id' => $record->id,
                    ]);

                    ActivityLog::create([
                        'action' => 'Attendance Record Created',
                        'details' => "Attendance recorded for employee ID {$attendanceData['employee_id']} on {$attendanceDate} with status {$attendanceData['status']}" .
                            ($attendanceData['overtime_hours'] ? " and {$attendanceData['overtime_hours']} overtime hours" : "") .
                            ($attendanceData['notes'] ? " with notes: {$attendanceData['notes']}" : ""),
                        'user' => 'HR',
                    ]);
                } catch (QueryException $e) {
                    Log::error('Failed to save attendance record', [
                        'user_email' => $userEmail,
                        'employee_id' => $attendanceData['employee_id'],
                        'date' => $attendanceDate,
                        'error_message' => $e->getMessage(),
                        'record_index' => $index,
                    ]);

                    ActivityLog::create([
                        'action' => 'Error: Attendance Record Creation Failed',
                        'details' => "Failed to record attendance for employee ID {$attendanceData['employee_id']} on {$attendanceDate}: {$e->getMessage()}",
                        'user' => 'HR',
                    ]);
                }
            }

            if ($savedRecords === 0) {
                Log::error('No attendance records were saved', [
                    'user_email' => $userEmail,
                    'date' => $attendanceDate,
                    'attempted_records' => count($validated['attendance']),
                    'existing_records' => $existingRecords,
                ]);

                ActivityLog::create([
                    'action' => 'Error: No Attendance Records Saved',
                    'details' => "No attendance records were saved for {$attendanceDate} by {$userEmail}",
                    'user' => 'HR',
                ]);
                return redirect()->route('EmployeeAttendence')->with('error', 'No new attendance records were saved. All selected employees may already have attendance recorded.');
            }

            Log::info('Attendance submission completed', [
                'user_email' => $userEmail,
                'date' => $attendanceDate,
                'saved_record_count' => $savedRecords,
            ]);

            ActivityLog::create([
                'action' => 'Attendance Submitted',
                'details' => "User {$userEmail} successfully submitted {$savedRecords} attendance records for {$attendanceDate}",
                'user' => 'HR',
            ]);

            return redirect()->route('EmployeeAttendence')->with('success', "Successfully submitted {$savedRecords} attendance records!");
        } catch (ValidationException $e) {
            Log::error('Attendance submission validation failed', [
                'user_email' => $userEmail,
                'errors' => $e->errors(),
                'input_data' => $request->all(),
            ]);

            ActivityLog::create([
                'action' => 'Error: Validation Failed',
                'details' => "Attendance submission failed for {$userEmail} due to validation errors: " . json_encode($e->errors()),
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
                'details' => "Unexpected error for {$userEmail} during attendance submission: {$e->getMessage()}",
                'user' => 'HR',
            ]);

            return redirect()->route('EmployeeAttendence')->with('error', 'An unexpected error occurred. Please try again.');
        }
    }
}
