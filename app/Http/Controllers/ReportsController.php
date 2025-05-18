<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Workman;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class ReportsController extends Controller
{
    /**
     * Display the reports page.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $date = $request->input('date', '2025-05-18'); // Default to today

        // Fetch attendance records for the selected date
        $attendances = Attendance::with('workman')
            ->where('attendance_date', $date)
            ->get();

        // Data for Attendance Status Chart
        $attendanceSummary = Attendance::where('attendance_date', $date)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $attendanceChartData = [
            'labels' => ['Present', 'Absent', 'Leave'],
            'data' => [
                $attendanceSummary['present'] ?? 0,
                $attendanceSummary['absent'] ?? 0,
                $attendanceSummary['leave'] ?? 0,
            ],
        ];

        // Data for Department Distribution Chart
        $departmentSummary = Workman::selectRaw('designation, COUNT(*) as count')
            ->whereIn('id', $attendances->pluck('workman_id'))
            ->groupBy('designation')
            ->pluck('count', 'designation')
            ->toArray();

        $departmentChartData = [
            'labels' => array_keys($departmentSummary),
            'data' => array_values($departmentSummary),
        ];

        return view('reports', compact('attendances', 'date', 'attendanceChartData', 'departmentChartData'));
    }

    /**
     * Download attendance report as PDF.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf(Request $request)
    {
        $date = $request->input('date', '2025-05-18');
        $attendances = Attendance::with('workman')
            ->where('attendance_date', $date)
            ->get();

        $pdf = Pdf::loadView('reports.pdf', compact('attendances', 'date'));
        return $pdf->download('attendance-report-' . $date . '.pdf');
    }

    /**
     * Download attendance report as CSV.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function downloadCsv(Request $request)
    {
        $date = $request->input('date', '2025-05-18');
        $attendances = Attendance::with('workman')
            ->where('attendance_date', $date)
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="attendance-report-' . $date . '.csv"',
        ];

        $callback = function () use ($attendances) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Employee Name', 'Department', 'Status', 'Notes', 'Date']);

            foreach ($attendances as $attendance) {
                $row = [
                    $attendance->workman->name . ' ' . $attendance->workman->surname,
                    $attendance->workman->designation ?? 'N/A',
                    $attendance->status,
                    $attendance->notes ?? '',
                    $attendance->attendance_date->format('Y-m-d'),
                ];
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
