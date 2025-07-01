<?php

namespace App\Http\Controllers;

use App\Models\EmployeeAddition;
use App\Models\Location;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeAdditionController extends Controller
{
    /**
     * Display a listing of the employee additions and render the management view.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $locations = Location::all();
        $query = EmployeeAddition::with('employee');

        // Apply location filter if provided
        if ($request->has('location_id') && $request->location_id) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('location_id', $request->location_id);
            });
        }

        // Handle AJAX requests for table data
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

        // Load additions for initial page render
        $additions = $query->get();
        return view('addition.index', compact('additions', 'locations'));
    }

    /**
     * Fetch employees by location for AJAX requests.
     *
     * @param int $locationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmployeesByLocation($locationId)
    {
        $employees = Employee::where('location_id', $locationId)->get(['id', 'name']);
        return response()->json(['employees' => $employees]);
    }

    /**
     * Store newly created employee additions in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'employee_id' => 'required|exists:employees,id',
            'additions' => 'required|array',
            'additions.*.type' => 'required|string|max:255',
            'additions.*.rate' => 'required|numeric|min:0',
        ]);

        foreach ($request->additions as $additionData) {
            EmployeeAddition::create([
                'employee_id' => $request->employee_id,
                'type' => $additionData['type'],
                'rate' => $additionData['rate'],
            ]);
        }

        return redirect()->route('additions.index')->with('success', 'Additions created successfully.');
    }

    /**
     * Show the form for editing the specified employee addition (for AJAX).
     *
     * @param \App\Models\EmployeeAddition $addition
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(EmployeeAddition $addition)
    {
        $addition->load('employee');
        return response()->json(['addition' => $addition]);
    }

    /**
     * Update the specified employee addition in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\EmployeeAddition $addition
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, EmployeeAddition $addition)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'employee_id' => 'required|exists:employees,id',
            'additions' => 'required|array|min:1',
            'additions.*.type' => 'required|string|max:255',
            'additions.*.rate' => 'required|numeric|min:0',
        ]);

        $addition->update([
            'employee_id' => $request->employee_id,
            'type' => $request->additions[0]['type'],
            'rate' => $request->additions[0]['rate'],
        ]);

        return redirect()->route('additions.index')->with('success', 'Addition updated successfully.');
    }

    /**
     * Remove the specified employee addition from storage.
     *
     * @param \App\Models\EmployeeAddition $addition
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(EmployeeAddition $addition)
    {
        $addition->delete();
        return redirect()->route('additions.index')->with('success', 'Addition deleted successfully.');
    }
}
