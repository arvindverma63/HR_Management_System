<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDeduction;
use Illuminate\Http\Request;

class EmployeeDeductionController extends Controller
{
    public function index(Request $request)
    {
        $employee = null;
        $deductions = [];

        if ($request->has('employee_unique_id')) {
            $employee = Employee::where('employee_unique_id', $request->employee_unique_id)->first();
            if ($employee) {
                $deductions = EmployeeDeduction::where('employee_unique_id', $employee->employee_unique_id)->get();
            }
        }

        return view('HRAdmin.employee-deductions', compact('employee', 'deductions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_unique_id' => 'required|exists:employees,employee_unique_id',
            'type' => 'required|string',
            'rate' => 'required|numeric',
        ]);

        EmployeeDeduction::create([
            'employee_unique_id' => $request->employee_unique_id,
            'type' => $request->type,
            'rate' => $request->rate,
        ]);

        return redirect()->back()->with('success', 'Deduction added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string',
            'rate' => 'required|numeric',
        ]);

        $deduction = EmployeeDeduction::findOrFail($id);
        $deduction->update([
            'type' => $request->type,
            'rate' => $request->rate,
        ]);

        return redirect()->back()->with('success', 'Deduction updated successfully.');
    }

    public function destroy($id)
    {
        $deduction = EmployeeDeduction::findOrFail($id);
        $deduction->delete();

        return redirect()->back()->with('success', 'Deduction deleted successfully.');
    }
}
