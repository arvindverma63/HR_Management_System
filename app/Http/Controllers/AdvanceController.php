<?php

namespace App\Http\Controllers;

use App\Models\Advance;
use App\Models\Employee;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdvanceController extends Controller
{
    /**
     * Display a listing of advances.
     */
    public function index()
    {
        $advances = Advance::with('employee')->paginate(10);
        return view('advances.index', compact('advances'));
    }

    /**
     * Show the form for creating a new advance.
     */
    public function create(Request $request)
    {
        $locations = Location::all();
        $location_id = $request->input('location_id');
        $employee_search = $request->input('employee_search');

        $query = Employee::query();

        if ($location_id) {
            $query->where('location_id', $location_id);
        }

        if ($employee_search) {
            $query->where('employee_unique_id', 'like', '%' . $employee_search . '%');
        }

        $employees = $query->get();

        return view('advances.create', compact('locations', 'employees', 'location_id', 'employee_search'));
    }

    /**
     * Store a newly created advance.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'money' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:0,1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Advance::create($request->only([
            'employee_id',
            'money',
            'notes',
            'status'
        ]));

        return redirect()->route('advances.index')->with('success', 'Advance created successfully');
    }

    /**
     * Display the specified advance.
     */
    public function show($id)
    {
        $advance = Advance::with('employee')->findOrFail($id);
        return view('advances.show', compact('advance'));
    }

    /**
     * Show the form for editing the specified advance.
     */
    public function edit($id, Request $request)
    {
        $advance = Advance::findOrFail($id);
        $locations = Location::all();
        $location_id = $request->input('location_id');
        $employee_search = $request->input('employee_search');

        $query = Employee::query();

        if ($location_id) {
            $query->where('location_id', $location_id);
        }

        if ($employee_search) {
            $query->where('name', 'like', '%' . $employee_search . '%');
        }

        $employees = $query->get();

        return view('advances.edit', compact('advance', 'locations', 'employees', 'location_id', 'employee_search'));
    }

    /**
     * Update the specified advance.
     */
    public function update(Request $request, $id)
    {
        $advance = Advance::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'employee_id' => 'sometimes|exists:employees,id',
            'money' => 'sometimes|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:0,1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $advance->update($request->only([
            'employee_id',
            'money',
            'notes',
            'status'
        ]));

        return redirect()->route('advances.index')->with('success', 'Advance updated successfully');
    }

    /**
     * Remove the specified advance.
     */
    public function destroy($id)
    {
        $advance = Advance::findOrFail($id);
        $advance->delete();
        return redirect()->route('advances.index')->with('success', 'Advance deleted successfully');
    }

    /**
     * Update advance status (0 or 1).
     */
    public function updateStatus(Request $request, $id)
    {
        $advance = Advance::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:0,1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $advance->update([
            'status' => $request->status
        ]);

        return redirect()->route('advances.index')->with('success', 'Advance status updated successfully');
    }
}
