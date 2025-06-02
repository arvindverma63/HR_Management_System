<?php

namespace App\Http\Controllers;

use App\Models\Workman;
use App\Models\Attendance;
use App\Models\Location;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display the attendance form.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $search = $request->input('search', '');

        // Fetch workmen with their locations, filter by search term if provided
        $workmenQuery = Workman::with('location')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('surname', 'like', "%{$search}%");
            });

        // Paginate workmen
        $workmen = $workmenQuery->paginate(10);

        // Check if attendance has already been submitted for the selected date
        $attendanceExists = Attendance::where('attendance_date', $date)->exists();

        return view('attendance', compact('workmen', 'date', 'search', 'attendanceExists'));
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
            'attendance.*.workman_id' => 'required|exists:workmen,id',
            'attendance.*.location_id' => 'required|exists:locations,id',
            'attendance.*.status' => 'required|in:present,absent,leave',
            'attendance.*.overtime_hours' => 'nullable|string|max:500',
        ]);

        $attendanceDate = $validated['attendance_date'];

        // Check if attendance has already been submitted for this date
        if (Attendance::where('attendance_date', $attendanceDate)->exists()) {
            return redirect()->route('attendence')->with('error', 'Attendance for this date has already been submitted and cannot be edited.');
        }

        // Store attendance records
        foreach ($validated['attendance'] as $attendanceData) {
            Attendance::create([
                'workman_id' => $attendanceData['workman_id'],
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
            'user' => 'Admin',
        ]);

        return redirect()->route('attendence')->with('success', 'Attendance submitted successfully!');
    }
}
