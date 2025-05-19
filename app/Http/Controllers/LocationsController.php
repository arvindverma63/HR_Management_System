<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class LocationsController extends Controller
{
    /**
     * Display a listing of the locations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $locations = Location::paginate(10);
        return view('locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new location.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Since the index view now handles creation via a form, redirect to index
        return redirect()->route('locations.index');
    }

    /**
     * Store a newly created location in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'contact_number' => 'nullable|string|max:15',
                'start_shift_time' => 'nullable|date_format:H:i',
                'end_shift_time' => 'nullable|date_format:H:i|after:start_shift_time',
            ], [
                'name.required' => 'The location name is required.',
                'contact_number.max' => 'The contact number cannot exceed 15 characters.',
                'start_shift_time.date_format' => 'The start shift time must be in the format HH:MM (e.g., 09:00).',
                'end_shift_time.date_format' => 'The end shift time must be in the format HH:MM (e.g., 17:00).',
                'end_shift_time.after' => 'The end shift time must be after the start shift time.',
            ]);

            $location = Location::create($validated);

            ActivityLog::create([
                'action' => 'Location Added',
                'details' => "Location: {$location->name}",
                'user' => Auth::user()->name ?? 'Admin',
            ]);

            return redirect()->route('locations.index')->with('success', "Location '{$location->name}' added successfully!");
        } catch (Exception $e) {
            return redirect()->route('locations.index')->with('error', 'Failed to add location: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified location.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\View\View
     */
    public function edit(Location $location)
    {
        // Since the index view now uses a modal for editing, redirect to index
        return redirect()->route('locations.index');
    }

    /**
     * Update the specified location in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Location $location)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'contact_number' => 'nullable|string|max:15',
                'start_shift_time' => 'nullable|date_format:H:i',
                'end_shift_time' => 'nullable|date_format:H:i|after:start_shift_time',
            ], [
                'name.required' => 'The location name is required.',
                'contact_number.max' => 'The contact number cannot exceed 15 characters.',
                'start_shift_time.date_format' => 'The start shift time must be in the format HH:MM (e.g., 09:00).',
                'end_shift_time.date_format' => 'The end shift time must be in the format HH:MM (e.g., 17:00).',
                'end_shift_time.after' => 'The end shift time must be after the start shift time.',
            ]);

            $location->update($validated);

            ActivityLog::create([
                'action' => 'Location Updated',
                'details' => "Location: {$location->name}",
                'user' => Auth::user()->name ?? 'Admin',
            ]);

            return redirect()->route('locations.index')->with('success', "Location '{$location->name}' updated successfully!");
        } catch (Exception $e) {
            return redirect()->route('locations.index')->with('error', 'Failed to update location: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified location from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Location $location)
    {
        try {
            $locationName = $location->name;
            $location->delete();

            ActivityLog::create([
                'action' => 'Location Deleted',
                'details' => "Location: {$locationName}",
                'user' => Auth::user()->name ?? 'Admin',
            ]);

            return redirect()->route('locations.index')->with('success', "Location '{$locationName}' deleted successfully!");
        } catch (Exception $e) {
            return redirect()->route('locations.index')->with('error', 'Failed to delete location: ' . $e->getMessage());
        }
    }
}
