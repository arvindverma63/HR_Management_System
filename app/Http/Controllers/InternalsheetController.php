<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeAttendence;
use Carbon\Carbon;
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

        $startDate = Carbon::createFromDate($validated['year'], $validated['month'], 1)->startOfMonth();
        $endDate = Carbon::createFromDate($validated['year'], $validated['month'], 1)->endOfMonth();

        $attendances = EmployeeAttendence::whereBetween('created_at', [$startDate, $endDate])->get();
        $report = [];

        foreach ($attendances as $att) {
            $startShift = $att->location->start_shift_time;
            $endShift = $att->location->end_shift_time;

            if (!$startShift || !$endShift) continue;

            $start = Carbon::createFromFormat('H:i:s', $startShift);
            $end = Carbon::createFromFormat('H:i:s', $endShift);
            if ($end->lessThan($start)) $end->addDay();

            $minutes = $end->diffInMinutes($start);
            $hours = round($minutes / 60, 2);

            $ratePerMonth = $att->employee->monthly_rate ?? 0;
            $hourlyPay = $att->employee->hourly_pay ?? 0;
            $otRate = $att->employee->ot_rate ?? $hourlyPay; // fallback to hourly if no ot rate

            $daysWorked = $att->status === 'present' ? 1 : 0;

            $overtimeHours = $att->overtime_hours ?? 0;

            $basicEarnings = $att->status == 'present' ? $hourlyPay * $hours : 0;
            $overtimeEarnings = $att->status == 'present' && $overtimeHours > 0 ? $otRate * $overtimeHours : 0;
            $otherEarnings = $att->other_earnings ?? 0;

            $cashDeduction = $att->cash_deduction ?? 0;
            $miscRecovery = $att->misc_recovery ?? 0;
            $bankAdv = $att->bank_adv ?? 0;

            $totalDeduction = $cashDeduction + $miscRecovery + $bankAdv;
            $netPayments = ($basicEarnings + $overtimeEarnings + $otherEarnings) - $totalDeduction;

            $report[] = (object)[
                'name' => $att->employee->name,
                'rate_per_month' => $ratePerMonth,
                'rate_of_wages' => $hourlyPay,
                'rate_of_ot' => $otRate,
                'days_worked' => $daysWorked,
                'overtime_hours' => $overtimeHours,
                'basic_earnings' => $basicEarnings,
                'overtime_earnings' => $overtimeEarnings,
                'other_earnings' => $otherEarnings,
                'cash_deduction' => $cashDeduction,
                'misc_recovery' => $miscRecovery,
                'bank_adv' => $bankAdv,
                'total_deduction' => $totalDeduction,
                'net_payments' => $netPayments,
            ];
        }

        return view('internalSheet', ['report' => $report]);
    }
}
