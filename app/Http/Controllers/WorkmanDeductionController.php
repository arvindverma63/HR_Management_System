<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\WorkmanDeduction;
use App\Models\Workman;
use Illuminate\Http\Request;

class WorkmanDeductionController extends Controller
{
    // WorkmanDeductionController.php
    public function index(Request $request)
    {
        $location = null;
        $deductions = [];

        if ($request->filled('location_id')) {
            $location = Location::find($request->location_id);
            if ($location) {
                $deductions = $location->workmanDeduction;
            }
        }

        return view('workman-deductions', compact('location', 'deductions'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'type' => 'required|string',
            'rate' => 'required|numeric',
        ]);

        WorkmanDeduction::create($validated);
        return redirect()->back()->with('success', 'Deduction added!');
    }

    public function update(Request $request, $id)
    {
        $deduction = WorkmanDeduction::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|string',
            'rate' => 'required|numeric',
        ]);

        $deduction->update($validated);
        return redirect()->back()->with('success', 'Deduction updated!');
    }

    public function destroy($id)
    {
        WorkmanDeduction::destroy($id);
        return redirect()->back()->with('success', 'Deduction deleted!');
    }
}
