<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Employee;
use App\Models\EmployeeAttendence;
use App\Models\Workman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeAttendenceController extends Controller
{
    public function index(Request $request)
    {
        $email = Auth::user()->email;
        $workman = Workman::where('email', $email)->first();

        if (!$workman) {
            abort(404, 'Workman not found.');
        }

        $date = $request->input('date', now()->toDateString());
        $search = $request->input('search', '');

        // Fetch employees with their locations, filter by search term if provided
        $workmenQuery = Employee::with('location')
            ->where('location_id', $workman->location_id)
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('surname', 'like', "%{$search}%");
                });
            });

        $workmen = $workmenQuery->paginate(10);

        // Check if attendance has already been submitted for the selected date
        $attendanceExists = EmployeeAttendence::where('attendance_date', $date)->exists();

        return view('HRAdmin.employeeAttendence', compact('workmen', 'date', 'search', 'attendanceExists'));
    }


    /**
     * Store the attendance records.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'attendance_date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.employee_id' => 'required|exists:workmen,id',
            'attendance.*.location_id' => 'required|exists:locations,id',
            'attendance.*.status' => 'required|in:present,absent,leave',
            'attendance.*.overtime_hours' => 'nullable|max:500',
        ]);

        $attendanceDate = $validated['attendance_date'];

        // Check if attendance has already been submitted for this date
        if (EmployeeAttendence::where('attendance_date', $attendanceDate)->exists()) {
            return redirect()->route('EmployeeAttendence')->with('error', 'Attendance for this date has already been submitted and cannot be edited.');
        }

        // Store attendance records
        foreach ($validated['attendance'] as $attendanceData) {
            EmployeeAttendence::create([
                'employee_id' => $attendanceData['employee_id'],
                'location_id' => $attendanceData['location_id'],
                'attendance_date' => $attendanceDate,
                'status' => $attendanceData['status'],
                'overtime_hours' => $attendanceData['overtime_hours'],
            ]);
        }

        // Log the activity
        ActivityLog::create([
            'action' => 'Attendance Submitted',
            'details' => "For {$attendanceDate}",
            'user' => 'HR',
        ]);

        return redirect()->route('EmployeeAttendence')->with('success', 'Attendance submitted successfully!');
    }
}
