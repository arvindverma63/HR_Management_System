<?php

namespace App\Http\Controllers;

use App\Models\SkillType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SkillTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skillTypes = SkillType::all();
        return view('skilltype.index', compact('skillTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('skilltype.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'percentage' => 'required|numeric|between:0,100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        SkillType::create($request->all());
        return redirect()->route('skilltype.index')->with('success', 'Skill Type created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $skillType = SkillType::find($id);
        return view('skilltype.edit', compact('skillType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SkillType $skillType)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'percentage' => 'required|numeric|between:0,100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $skillType->update($request->all());
        return redirect()->route('skilltype.index')->with('success', 'Skill Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SkillType $skillType)
    {
        $skillType->delete();
        return redirect()->route('skilltype.index')->with('success', 'Skill Type deleted successfully.');
    }
}
