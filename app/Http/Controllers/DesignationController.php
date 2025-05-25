<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = Designation::all();
        return view('designation', ['designations' => $designations]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:80',
        ]);

        if (Designation::create($validated)) {
            return redirect()->route('designation.index')->with('success', 'Designation added successfully');
        } else {
            return redirect()->route('designation.index')->with('error', 'Something went wrong');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:80',
        ]);

        $designation = Designation::findOrFail($id);
        $designation->update(['name' => $request->name]);

        return redirect()->route('designation.index')->with('success', 'Designation updated successfully.');
    }

    public function destroy($id)
    {
        $designation = Designation::find($id);

        if ($designation) {
            $designation->delete();
            return redirect()->route('designation.index')->with('success', 'Designation deleted successfully');
        }

        return redirect()->route('designation.index')->with('error', 'Designation not found');
    }
}
