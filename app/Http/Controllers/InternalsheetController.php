<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeAttendence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InternalsheetController extends Controller
{
    public function HRIndex()
    {
        return view('internalSheet');
    }

    public function getHRReport(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:1900|max:9999',
        ]);

        $report = Employee::select([
            'employees.id',
            'employees.name', // Added for report
            'employees.designation', // Added for report
            'employees.monthly_rate as rate_per_month',
            DB::raw('ROUND(employees.monthly_rate / 28, 2) as rate_of_wages'),
            DB::raw('ROUND(employees.hourly_pay, 2) as rate_of_ot'),
            DB::raw('COUNT(DISTINCT CASE WHEN ea.status = "present" THEN ea.attendance_date END) as days_worked'),
            DB::raw('COALESCE(SUM(ea.overtime_hours), 0) as overtime_hours'),
            // Calculate shift duration in hours: (end_shift_time - start_shift_time) / 3600
            DB::raw('TIMESTAMPDIFF(SECOND, locations.start_shift_time, locations.end_shift_time) / 3600 as shift_duration'),
            // Monthly basic earnings: shift_duration * hourly_pay * days_worked
            DB::raw('ROUND((TIMESTAMPDIFF(SECOND, locations.start_shift_time, locations.end_shift_time) / 3600) * employees.hourly_pay * COUNT(DISTINCT CASE WHEN ea.status = "present" THEN ea.attendance_date END), 2) as basic_earnings'),
            DB::raw('COALESCE(SUM(ea.overtime_hours * employees.hourly_pay), 0) as overtime_earnings'),
            DB::raw('0 as other_earnings'),
            // Total earnings: basic_earnings + overtime_earnings + other_earnings
            DB::raw('ROUND((TIMESTAMPDIFF(SECOND, locations.start_shift_time, locations.end_shift_time) / 3600) * employees.hourly_pay * COUNT(DISTINCT CASE WHEN ea.status = "present" THEN ea.attendance_date END) + COALESCE(SUM(ea.overtime_hours * employees.hourly_pay), 0), 2) as total_earnings'),
            // Deductions (set to 0 since no deductions table exists)
            DB::raw('0 as cash_deduction'),
            DB::raw('0 as misc_deduction'),
            DB::raw('0 as recovery_deduction'),
            DB::raw('0 as bank_adv_deduction'),
            // Total deductions: sum of all deductions (0)
            DB::raw('0 as total_deductions'),
            // Net payments: total_earnings - total_deductions
            DB::raw('ROUND((TIMESTAMPDIFF(SECOND, locations.start_shift_time, locations.end_shift_time) / 3600) * employees.hourly_pay * COUNT(DISTINCT CASE WHEN ea.status = "present" THEN ea.attendance_date END) + COALESCE(SUM(ea.overtime_hours * employees.hourly_pay), 0), 2) as net_payments'),
        ])
            ->leftJoin('employee_attendence as ea', function ($join) use ($validated) {
                $join->on('employees.id', '=', 'ea.employee_id')
                    ->whereMonth('ea.attendance_date', $validated['month'])
                    ->whereYear('ea.attendance_date', $validated['year']);
            })
            ->leftJoin('locations', 'employees.location_id', '=', 'locations.id')
            ->whereExists(function ($query) use ($validated) {
                $query->select(DB::raw(1))
                    ->from('employee_attendence')
                    ->whereColumn('employee_attendence.employee_id', 'employees.id')
                    ->whereMonth('employee_attendence.attendance_date', $validated['month'])
                    ->whereYear('employee_attendence.attendance_date', $validated['year'])
                    ->where('employee_attendence.status', 'present');
            })
            ->groupBy(
                'employees.id',
                'employees.name',
                'employees.designation',
                'employees.monthly_rate',
                'employees.hourly_pay',
                'locations.start_shift_time',
                'locations.end_shift_time'
            )
            ->get();

        return view('internalSheet', ['report' => $report]);
    }
}
