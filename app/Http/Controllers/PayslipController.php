<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\EmployeeAttendence;
use App\Models\EmployeeDeduction;
use App\Models\WorkmanDeduction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PayslipController extends Controller
{
    public function getInternalSlip(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:1900|max:9999',
            'id' => 'required|integer'
        ]);

        $startDate = Carbon::createFromDate($validated['year'], $validated['month'], 1)->startOfMonth();
        $endDate = Carbon::createFromDate($validated['year'], $validated['month'], 1)->endOfMonth();

        $attendances = Attendance::with('workman', 'location')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('workman_id', $request->id)
            ->get();

        $report = [];

        // Calculate deductions once for the workman
        $cashDeduction = WorkmanDeduction::where('workman_id', $request->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'CASH')
            ->sum('rate');

        $miscRecovery = WorkmanDeduction::where('workman_id', $request->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'MISC')
            ->sum('rate');

        $bankAdv = WorkmanDeduction::where('workman_id', $request->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'BANK ADV')
            ->sum('rate');

        $totalDeduction = $cashDeduction + $miscRecovery + $bankAdv;

        foreach ($attendances as $att) {
            $workmanId = $att->workman->id;
            $workmanName = $att->workman->name;

            $startShift = $att->location->start_shift_time;
            $endShift = $att->location->end_shift_time;

            if (!$startShift || !$endShift) {
                continue;
            }

            $start = Carbon::createFromFormat('H:i:s', $startShift);
            $end = Carbon::createFromFormat('H:i:s', $endShift);
            if ($end->lessThan($start)) {
                $end->addDay();
            }

            $minutes = $end->diffInMinutes($start);
            $hours = round($minutes / 60, 2);

            $ratePerMonth = $att->workman->monthly_rate ?? 0;
            $hourlyPay = $att->workman->hourly_pay ?? 0;
            $otRate = $att->workman->ot_rate ?? $hourlyPay;

            $daysWorked = $att->status === 'present' ? 1 : 0;
            $overtimeHours = $att->overtime_hours ?? 0;

            $basicEarnings = $att->status === 'present' ? $hourlyPay * $hours : 0;
            $overtimeEarnings = $att->status === 'present' && $overtimeHours > 0 ? $otRate * $overtimeHours : 0;
            $otherEarnings = $att->other_earnings ?? 0;

            if (!isset($report[$workmanId])) {
                $report[$workmanId] = (object)[
                    'name' => $workmanName,
                    'rate_per_month' => $ratePerMonth,
                    'rate_of_wages' => $hourlyPay,
                    'rate_of_ot' => $otRate,
                    'days_worked' => 0,
                    'overtime_hours' => 0,
                    'basic_earnings' => 0,
                    'overtime_earnings' => 0,
                    'other_earnings' => 0,
                    'cash_deduction' => $cashDeduction,
                    'misc_recovery' => $miscRecovery,
                    'bank_adv' => $bankAdv,
                    'total_deduction' => $totalDeduction,
                    'net_payments' => 0,
                    'id' => $workmanId,
                ];
            }

            // Accumulate attendance-based metrics
            $report[$workmanId]->days_worked += $daysWorked;
            $report[$workmanId]->overtime_hours += $overtimeHours;
            $report[$workmanId]->basic_earnings += $basicEarnings;
            $report[$workmanId]->overtime_earnings += $overtimeEarnings;
            $report[$workmanId]->other_earnings += $otherEarnings;
            $report[$workmanId]->net_payments += ($basicEarnings + $overtimeEarnings + $otherEarnings);
        }

        // Adjust net payments for deductions
        foreach ($report as $workmanId => $data) {
            $report[$workmanId]->net_payments -= $data->total_deduction;
        }

        $report = array_values($report); // Convert to indexed array

        return view('slips.employee-pay-slip', ['report' => $report]);
    }

    public function getEmployeeSlip(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:1900|max:9999',
            'id' => 'required|integer'
        ]);

        $startDate = Carbon::createFromDate($validated['year'], $validated['month'], 1)->startOfMonth();
        $endDate = Carbon::createFromDate($validated['year'], $validated['month'], 1)->endOfMonth();

        $attendances = EmployeeAttendence::with('employee', 'location')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('employee_id', $request->id)
            ->get();

        $report = [];

        // Calculate deductions once for the employee
        $cashDeduction = EmployeeDeduction::where('employee_id', $request->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'CASH')
            ->sum('rate');

        $miscRecovery = EmployeeDeduction::where('employee_id', $request->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'MISC')
            ->sum('rate');

        $bankAdv = EmployeeDeduction::where('employee_id', $request->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'BANK ADV')
            ->sum('rate');

        $totalDeduction = $cashDeduction + $miscRecovery + $bankAdv;

        foreach ($attendances as $att) {
            $employeeId = $att->employee->id;
            $employeeName = $att->employee->name;

            $startShift = $att->location->start_shift_time;
            $endShift = $att->location->end_shift_time;

            if (!$startShift || !$endShift) {
                continue;
            }

            $start = Carbon::createFromFormat('H:i:s', $startShift);
            $end = Carbon::createFromFormat('H:i:s', $endShift);
            if ($end->lessThan($start)) {
                $end->addDay();
            }

            $minutes = $end->diffInMinutes($start);
            $hours = round($minutes / 60, 2);

            $ratePerMonth = $att->employee->monthly_rate ?? 0;
            $hourlyPay = $att->employee->hourly_pay ?? 0;
            $otRate = $att->employee->ot_rate ?? $hourlyPay;

            $daysWorked = $att->status === 'present' ? 1 : 0;
            $overtimeHours = $att->overtime_hours ?? 0;

            $basicEarnings = $att->status === 'present' ? $hourlyPay * $hours : 0;
            $overtimeEarnings = $att->status === 'present' && $overtimeHours > 0 ? $otRate * $overtimeHours : 0;
            $otherEarnings = $att->other_earnings ?? 0;

            if (!isset($report[$employeeId])) {
                $report[$employeeId] = (object)[
                    'name' => $employeeName,
                    'rate_per_month' => $ratePerMonth,
                    'rate_of_wages' => $hourlyPay,
                    'rate_of_ot' => $otRate,
                    'days_worked' => 0,
                    'overtime_hours' => 0,
                    'basic_earnings' => 0,
                    'overtime_earnings' => 0,
                    'other_earnings' => 0,
                    'cash_deduction' => $cashDeduction,
                    'misc_recovery' => $miscRecovery,
                    'bank_adv' => $bankAdv,
                    'total_deduction' => $totalDeduction,
                    'net_payments' => 0,
                    'id' => $employeeId,
                ];
            }

            // Accumulate attendance-based metrics
            $report[$employeeId]->days_worked += $daysWorked;
            $report[$employeeId]->overtime_hours += $overtimeHours;
            $report[$employeeId]->basic_earnings += $basicEarnings;
            $report[$employeeId]->overtime_earnings += $overtimeEarnings;
            $report[$employeeId]->other_earnings += $otherEarnings;
            $report[$employeeId]->net_payments += ($basicEarnings + $overtimeEarnings + $otherEarnings);
        }

        // Adjust net payments for deductions
        foreach ($report as $employeeId => $data) {
            $report[$employeeId]->net_payments -= $data->total_deduction;
        }

        // Convert to array for view
        $report = array_values($report);

        return view('slips.employee-pay-slip', ['report' => $report]);
    }
}
