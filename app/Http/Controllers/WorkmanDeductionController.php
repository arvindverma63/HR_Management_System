<?php

namespace App\Http\Controllers;

use App\Models\WorkmanDeduction;
use App\Models\Workman;
use Illuminate\Http\Request;

class WorkmanDeductionController extends Controller
{
    // WorkmanDeductionController.php
    public function index(Request $request)
    {
        $workman = null;
        $deductions = [];

        if ($request->has('workman_unique_id')) {
            $workman = Workman::where('workman_unique_id', $request->workman_unique_id)->first();
            if ($workman) {
                $deductions = $workman->deductions; // define hasMany in Workman model
            }
        }

        return view('workman-deductions', compact('workman', 'deductions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'workman_unique_id' => 'required|exists:workmen,workman_unique_id',
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
