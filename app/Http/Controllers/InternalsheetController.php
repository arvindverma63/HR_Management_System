<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmployeeAttendence;
use App\Models\WorkmanDeduction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InternalsheetController extends Controller
{
    public function HRIndex()
    {
        return view('internalSheet');
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

            // Allowance fields
            $houseRentAllowance = $att->house_rent_allowance ?? 0;
            $conveyanceAllowance = $att->conveyance_allowance ?? 0;
            $foodAllowance = $att->food_allowance ?? 0;
            $siteAllowance = $att->site_allowance ?? 0;
            $statutoryBonus = $att->statutory_bonus ?? 0;
            $retrenchmentAllowance = $att->retrenchment_allowance ?? 0;
            $medical = $att->medical ?? 0;

            // Sunday check
            $dateOfAttendance = Carbon::parse($att->created_at);
            $isSunday = $dateOfAttendance->isSunday();
            $sundayWorked = ($isSunday && $att->status === 'present') ? 1 : 0;

            if (!isset($report[$employeeId])) {
                $report[$employeeId] = (object)[
                    'name' => $employeeName,
                    'employee_unique_id' => $att->employee->employee_unique_id,
                    'clims_id' => $att->employee->clims_id,
                    'id' => $employeeId,
                    'rate_per_month' => $ratePerMonth,
                    'rate_of_wages' => $hourlyPay,
                    'rate_of_ot' => $otRate,
                    'days_worked' => 0,
                    'sundays_worked' => 0,
                    'overtime_hours' => 0,
                    'basic_earnings' => 0,
                    'overtime_earnings' => 0,
                    'other_earnings' => 0,

                    // Allowances
                    'house_rent_allowance' => 0,
                    'conveyance_allowance' => 0,
                    'food_allowance' => 0,
                    'site_allowance' => 0,
                    'statutory_bonus' => 0,
                    'retrenchment_allowance' => 0,
                    'medical' => 0,

                    // Deductions
                    'cash_deduction' => 0,
                    'misc_recovery' => 0,
                    'bank_adv' => 0,
                    'total_deduction' => 0,

                    'net_payments' => 0,

                    'bank_ifsc' => $att->employee->bank_ifsc,
                    'bank_account' => $att->employee->bank_account,
                ];
            }

            // Accumulate
            $report[$employeeId]->days_worked += $daysWorked;
            $report[$employeeId]->sundays_worked += $sundayWorked;
            $report[$employeeId]->overtime_hours += $overtimeHours;
            $report[$employeeId]->basic_earnings += $basicEarnings;
            $report[$employeeId]->overtime_earnings += $overtimeEarnings;
            $report[$employeeId]->other_earnings += $otherEarnings;

            // Allowances
            $report[$employeeId]->house_rent_allowance += $houseRentAllowance;
            $report[$employeeId]->conveyance_allowance += $conveyanceAllowance;
            $report[$employeeId]->food_allowance += $foodAllowance;
            $report[$employeeId]->site_allowance += $siteAllowance;
            $report[$employeeId]->statutory_bonus += $statutoryBonus;
            $report[$employeeId]->retrenchment_allowance += $retrenchmentAllowance;
            $report[$employeeId]->medical += $medical;

            // Net payments before deductions
            $report[$employeeId]->net_payments += ($basicEarnings + $overtimeEarnings + $otherEarnings);

            // Deductions
            $report[$employeeId]->cash_deduction += $att->cash_deduction ?? 0;
            $report[$employeeId]->misc_recovery += $att->misc_recovery ?? 0;
            $report[$employeeId]->bank_adv += $att->bank_adv ?? 0;
        }

        // Finalize net payments
        foreach ($report as $employeeId => $data) {
            $data->total_deduction = $data->cash_deduction + $data->misc_recovery + $data->bank_adv;
            $data->net_payments -= $data->total_deduction;

            $allowancesTotal = $data->house_rent_allowance
                + $data->conveyance_allowance
                + $data->food_allowance
                + $data->site_allowance
                + $data->statutory_bonus
                + $data->retrenchment_allowance
                + $data->medical;

            $data->net_payments -= $allowancesTotal;
        }

        return view('employeeInternalSheet', ['report' => array_values($report)]);
    }




    public function employeeIndex()
    {
        return view('employeeInternalSheet');
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

            // New: Allowance fields
            $houseRentAllowance = $att->house_rent_allowance ?? 0;
            $conveyanceAllowance = $att->conveyance_allowance ?? 0;
            $foodAllowance = $att->food_allowance ?? 0;
            $siteAllowance = $att->site_allowance ?? 0;
            $statutoryBonus = $att->statutory_bonus ?? 0;
            $retrenchmentAllowance = $att->retrenchment_allowance ?? 0;
            $medical = $att->medical ?? 0;

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
                    'id' => $workmanId,
                    'name' => $workmanName,
                    'rate_per_month' => $ratePerMonth,
                    'rate_of_wages' => $hourlyPay,
                    'rate_of_ot' => $otRate,
                    'days_worked' => 0,
                    'overtime_hours' => 0,
                    'basic_earnings' => 0,
                    'overtime_earnings' => 0,
                    'other_earnings' => 0,

                    // Allowances
                    'house_rent_allowance' => 0,
                    'conveyance_allowance' => 0,
                    'food_allowance' => 0,
                    'site_allowance' => 0,
                    'statutory_bonus' => 0,
                    'retrenchment_allowance' => 0,
                    'medical' => 0,

                    // Deductions
                    'cash_deduction' => $cashDeduction,
                    'misc_recovery' => $miscRecovery,
                    'bank_adv' => $bankAdv,
                    'total_deduction' => $totalDeduction,

                    'net_payments' => 0,
                ];
            }

            // Accumulate metrics
            $report[$workmanId]->days_worked += $daysWorked;
            $report[$workmanId]->overtime_hours += $overtimeHours;
            $report[$workmanId]->basic_earnings += $basicEarnings;
            $report[$workmanId]->overtime_earnings += $overtimeEarnings;
            $report[$workmanId]->other_earnings += $otherEarnings;

            // Accumulate allowances
            $report[$workmanId]->house_rent_allowance += $houseRentAllowance;
            $report[$workmanId]->conveyance_allowance += $conveyanceAllowance;
            $report[$workmanId]->food_allowance += $foodAllowance;
            $report[$workmanId]->site_allowance += $siteAllowance;
            $report[$workmanId]->statutory_bonus += $statutoryBonus;
            $report[$workmanId]->retrenchment_allowance += $retrenchmentAllowance;
            $report[$workmanId]->medical += $medical;

            // Add base net pay before deductions & allowance subtraction
            $report[$workmanId]->net_payments += ($basicEarnings + $overtimeEarnings + $otherEarnings);
        }

        // Finalize: subtract deductions & allowances
        foreach ($report as $workmanId => $data) {
            // Already summed: total deduction
            $data->net_payments -= $data->total_deduction;

            $allowancesTotal = $data->house_rent_allowance
                + $data->conveyance_allowance
                + $data->food_allowance
                + $data->site_allowance
                + $data->statutory_bonus
                + $data->retrenchment_allowance
                + $data->medical;

            $data->net_payments -= $allowancesTotal;
        }

        $report = array_values($report);

        return view('internalSheet', ['report' => $report]);
    }
}
