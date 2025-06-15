<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Workman;
use App\Models\WorkmanDeduction;
use Illuminate\Http\Request;

class WorkmanDeductionController extends Controller
{
    public function index(Request $request)
    {
        $locations = Location::all();
        $workmen = [];
        $selectedLocation = null;

        if ($request->filled('location_id')) {
            $selectedLocation = Location::find($request->location_id);
            if ($selectedLocation) {
                $workmen = Workman::where('location_id', $request->location_id)->with('deductions')->get();
            }
        }

        return view('workman-deductions', compact('locations', 'workmen', 'selectedLocation'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'workman_id' => 'required|exists:workmen,id',
            'type' => 'required|string|in:CASH,MISC,BANK ADV',
            'rate' => 'required|numeric|min:0',
        ]);

        WorkmanDeduction::create($validated);

        return redirect()->back()->with('success', 'Deduction added!');
    }

    public function update(Request $request, $id)
    {
        $deduction = WorkmanDeduction::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|string|in:CASH,MISC,BANK ADV',
            'rate' => 'required|numeric|min:0',
        ]);

        $deduction->update($validated);

        return redirect()->back()->with('success', 'Deduction updated!');
    }

    public function destroy($id)
    {
        $deduction = WorkmanDeduction::findOrFail($id);
        $deduction->delete();

        return redirect()->back()->with('success', 'Deduction deleted!');
    }
}
