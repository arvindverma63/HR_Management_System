<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the locations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $locations = Location::all();
        return view('locations', compact('locations'));
    }

    /**
     * Store a newly created location in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
        ]);

        $location = Location::create($validated);

        // Log the activity
        ActivityLog::create([
            'action' => 'Location Added',
            'details' => "{$location->name} in {$location->city}",
            'user' => 'Admin',
        ]);

        return redirect()->route('locations')->with('success', 'Location added successfully!');
    }

    /**
     * Show the form for editing the specified location.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\View\View
     */
    public function edit(Location $location)
    {
        $locations = Location::all();
        return view('locations', compact('location', 'locations'));
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
        ]);

        $location->update($validated);

        // Log the activity
        ActivityLog::create([
            'action' => 'Location Updated',
            'details' => "{$location->name} in {$location->city}",
            'user' => 'Admin',
        ]);

        return redirect()->route('locations')->with('success', 'Location updated successfully!');
    }

    /**
     * Remove the specified location from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Location $location)
    {
        $locationName = $location->name;
        $locationCity = $location->city;

        $location->delete();

        // Log the activity
        ActivityLog::create([
            'action' => 'Location Deleted',
            'details' => "{$locationName} in {$locationCity}",
            'user' => 'Admin',
        ]);

        return redirect()->route('locations')->with('success', 'Location deleted successfully!');
    }
}
