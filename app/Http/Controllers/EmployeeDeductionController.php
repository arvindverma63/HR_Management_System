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
        $location = null;
        $deductions = [];

        if ($request->filled('location_id')) {
            $location = Location::find($request->location_id);
            if ($location) {
                $deductions = $location->employeeDeduction;
            }
        }

        return view('HRAdmin.employee-deductions', compact('location', 'deductions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'type' => 'required|string',
            'rate' => 'required|numeric',
        ]);

        EmployeeDeduction::create([
            'location_id' => $request->location_id,
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
