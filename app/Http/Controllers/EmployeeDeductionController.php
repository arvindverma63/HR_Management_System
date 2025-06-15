<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDeduction;
use App\Models\Location;
use Illuminate\Http\Request;

class EmployeeDeductionController extends Controller
{
    public function index(Request $request)
    {
        $locations = Location::all();
        $employees = [];
        $selectedLocation = null;

        if ($request->filled('location_id')) {
            $selectedLocation = Location::find($request->location_id);
            if ($selectedLocation) {
                $employees = Employee::where('location_id', $request->location_id)->with('deductions')->get();
            }
        }

        return view('HRAdmin.employee-deductions', compact('locations', 'employees', 'selectedLocation'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|string|in:CASH,MISC,BANK ADV',
            'rate' => 'required|numeric|min:0',
        ]);

        EmployeeDeduction::create($validated);

        return redirect()->back()->with('success', 'Deduction added successfully.');
    }

    public function update(Request $request, $id)
    {
        $deduction = EmployeeDeduction::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|string|in:CASH,MISC,BANK ADV',
            'rate' => 'required|numeric|min:0',
        ]);

        $deduction->update($validated);

        return redirect()->back()->with('success', 'Deduction updated successfully.');
    }

    public function destroy($id)
    {
        $deduction = EmployeeDeduction::findOrFail($id);
        $deduction->delete();

        return redirect()->back()->with('success', 'Deduction deleted successfully.');
    }
}
