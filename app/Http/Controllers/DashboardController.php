<?php

namespace App\Http\Controllers;

use App\Models\Workman;
use App\Models\Location;
use App\Models\Attendance;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Total Employees
        $totalEmployees = Workman::count();

        // Employees added this month (May 2025)
        $employeesThisMonth = Workman::whereBetween('created_at', [
            '2025-05-01 00:00:00',
            '2025-05-31 23:59:59'
        ])->count();

        // Total Locations
        $totalLocations = Location::count();
        $lastLocation = Location::latest()->first();

        // Today's Attendance (May 18, 2025)
        $today = '2025-05-18';
        $attendanceSummary = Attendance::where('attendance_date', $today)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $attendance = [
            'present' => $attendanceSummary['present'] ?? 0,
            'absent' => $attendanceSummary['absent'] ?? 0,
            'leave' => $attendanceSummary['leave'] ?? 0,
        ];

        // Recent Activity Logs (latest 3)
        $recentActivities = ActivityLog::orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('dashboard', compact('totalEmployees', 'employeesThisMonth', 'totalLocations', 'lastLocation', 'attendance', 'recentActivities'));
    }
}
