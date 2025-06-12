<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PayslipController extends Controller
{
    public function getInternalSlip(Request $request){
          $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:1900|max:9999',
            'id' => 'required|integer'
        ]);

        $startDate = Carbon::createFromDate($validated['year'], $validated['month'], 1)->startOfMonth();
        $endDate = Carbon::createFromDate($validated['year'], $validated['month'], 1)->endOfMonth();

        $attendances = Attendance::with('workman', 'location')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('workan_id',$request->id)
            ->get();

        $report = [];

        foreach ($attendances as $att) {
            $workmanId = $att->workman->id;
            $workmanName = $att->workman->name;

            $startShift = $att->location->start_shift_time;
            $endShift = $att->location->end_shift_time;

            if (!$startShift || !$endShift)
                continue;

            $start = Carbon::createFromFormat('H:i:s', $startShift);
            $end = Carbon::createFromFormat('H:i:s', $endShift);
            if ($end->lessThan($start))
                $end->addDay();

            $minutes = $end->diffInMinutes($start);
            $hours = round($minutes / 60, 2);

            $ratePerMonth = $att->workman->monthly_rate ?? 0;
            $hourlyPay = $att->workman->hourly_pay ?? 0;
            $otRate = $att->workman->ot_rate ?? $hourlyPay;

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
                    'cash_deduction' => 0,
                    'misc_recovery' => 0,
                    'bank_adv' => 0,
                    'total_deduction' => 0,
                    'net_payments' => 0,
                ];
            }

            // Accumulate
            $report[$workmanId]->days_worked += $daysWorked;
            $report[$workmanId]->overtime_hours += $overtimeHours;
            $report[$workmanId]->basic_earnings += $basicEarnings;
            $report[$workmanId]->overtime_earnings += $overtimeEarnings;
            $report[$workmanId]->other_earnings += $otherEarnings;
            $report[$workmanId]->cash_deduction += $cashDeduction;
            $report[$workmanId]->misc_recovery += $miscRecovery;
            $report[$workmanId]->bank_adv += $bankAdv;
            $report[$workmanId]->total_deduction += $totalDeduction;
            $report[$workmanId]->net_payments += $netPayments;
        }

        $report = array_values($report); // convert to indexed array

        return view('slips.employee-pay-slip', ['report' => $report]);
    }
}
