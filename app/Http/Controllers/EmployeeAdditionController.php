<?php

namespace App\Http\Controllers;
use App\Models\EmployeeAddition;
use App\Models\Location;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeAdditionController extends Controller
{
    public function index(Request $request)
    {
        $locations = Location::all();
        $query = EmployeeAddition::with('employee');

        if ($request->has('location_id') && $request->location_id) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('location_id', $request->location_id);
            });
        }

        if ($request->ajax()) {
            $additions = $query->get()->map(function ($addition) {
                return [
                    'id' => $addition->id,
                    'employee_name' => $addition->employee->name ?? 'N/A',
                    'type' => $addition->type,
                    'rate' => $addition->rate,
                ];
            });
            return response()->json(['additions' => $additions]);
        }

        $additions = $query->get();
        return view('addition.index', compact('additions', 'locations'));
    }

    public function create()
    {
        $locations = Location::all();
        $employees = Employee::all();
        return view('addition.create', compact('locations', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
        ]);

        EmployeeAddition::create($request->all());
        return redirect()->route('additions.index')->with('success', 'Addition created successfully.');
    }

    public function edit(EmployeeAddition $addition)
    {
        $locations = Location::all();
        $employees = Employee::all();
        return view('addition.edit', compact('addition', 'locations', 'employees'));
    }

    public function update(Request $request, EmployeeAddition $addition)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
        ]);

        $addition->update($request->all());
        return redirect()->route('additions.index')->with('success', 'Addition updated successfully.');
    }

    public function destroy(EmployeeAddition $addition)
    {
        $addition->delete();
        return redirect()->route('additions.index')->with('success', 'Addition deleted successfully.');
    }
}
