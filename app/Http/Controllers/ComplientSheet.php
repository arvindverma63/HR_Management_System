<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\EmployeeAttendence;
use App\Models\Workman;
use App\Models\WorkmanDeduction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ComplientSheet extends Controller
{

    public function workmanComplientSheet(){
        return view('complient.hr-complient-sheet');
    }

        public function getHRReport(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:1900|max:9999',
        ]);

        $startDate = Carbon::createFromDate($validated['year'], $validated['month'], 1)->startOfMonth();
        $endDate = Carbon::createFromDate($validated['year'], $validated['month'], 1)->endOfMonth();

        $attendances = Attendance::with('workman', 'location')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $report = [];

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
                // Calculate deductions once per workman for the entire month
                $cashDeduction = WorkmanDeduction::where('workman_id', $workmanId)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->where('type', 'CASH')
                    ->sum('rate');

                $miscRecovery = WorkmanDeduction::where('workman_id', $workmanId)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->where('type', 'MISC')
                    ->sum('rate');

                $bankAdv = WorkmanDeduction::where('workman_id', $workmanId)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->where('type', 'BANK ADV')
                    ->sum('rate');

                $totalDeduction = $cashDeduction + $miscRecovery + $bankAdv;

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

        // Adjust net payments for deductions after accumulating all earnings
        foreach ($report as $workmanId => $data) {
            $report[$workmanId]->net_payments -= $data->total_deduction;
        }

        $report = array_values($report); // Convert to indexed array

        return view('internalSheet', ['report' => $report]);
    }

    public function getEmployeeReport(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:1900|max:9999',
        ]);

        $startDate = Carbon::createFromDate($validated['year'], $validated['month'], 1)->startOfMonth();
        $endDate = Carbon::createFromDate($validated['year'], $validated['month'], 1)->endOfMonth();

        $attendances = EmployeeAttendence::with('employee', 'location')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $report = [];

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
                // Initialize deductions to 0, will sum them later
                $report[$employeeId] = (object)[
                    'name' => $employeeName,
                    'id' => $employeeId,
                    'rate_per_month' => $ratePerMonth,
                    'rate_of_wages' => $hourlyPay,
                    'rate_of_ot' => $otRate,
                    'days_worked' => 0,
                    'overtime_hours' => 0,
                    'basic_earnings' => 0,
                    'overtime_earnings' => 0,
                    'other_earnings' => 0,
                    'cash_deduction' => 0,
                    'misc_recovery' => 0,
                    'bank_adv' => 0,
                    'total_deduction' => 0,
                    'net_payments' => 0,
                ];
            }

            // Accumulate attendance-based metrics
            $report[$employeeId]->days_worked += $daysWorked;
            $report[$employeeId]->overtime_hours += $overtimeHours;
            $report[$employeeId]->basic_earnings += $basicEarnings;
            $report[$employeeId]->overtime_earnings += $overtimeEarnings;
            $report[$employeeId]->other_earnings += $otherEarnings;
            $report[$employeeId]->net_payments += ($basicEarnings + $overtimeEarnings + $otherEarnings);

            // Accumulate deductions for this attendance record
            $report[$employeeId]->cash_deduction += $att->cash_deduction ?? 0;
            $report[$employeeId]->misc_recovery += $att->misc_recovery ?? 0;
            $report[$employeeId]->bank_adv += $att->bank_adv ?? 0;
        }

        // Calculate total deductions and adjust net payments
        foreach ($report as $employeeId => $data) {
            $report[$employeeId]->total_deduction = $data->cash_deduction + $data->misc_recovery + $data->bank_adv;
            $report[$employeeId]->net_payments -= $data->total_deduction;
        }

        // Convert to array for view
        $report = array_values($report);

        return view('employeeInternalSheet', ['report' => $report]);
    }


    public function employeeIndex()
    {
        return view('employeeInternalSheet');
    }
}
