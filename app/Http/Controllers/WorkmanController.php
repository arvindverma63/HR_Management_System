<?php

namespace App\Http\Controllers;

use App\Models\Workman;
use App\Models\Location;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class WorkmanController extends Controller
{
    /**
     * Display a listing of all workmen.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $workmen = Workman::with('location')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('surname', 'like', "%{$search}%")
                      ->orWhere('designation', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('workmen', compact('workmen', 'search'));
    }

    /**
     * Show the form for creating a new workman.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $locations = Location::all();
        return view('new-workmen', compact('locations'));
    }

    /**
     * Store a newly created workman in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'sex' => 'nullable|in:male,female',
            'dob' => 'nullable|date',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'designation' => 'nullable|in:HSW,SSW,USW',
            'monthly_rate' => 'nullable|numeric|min:0',
            'handicapped' => 'nullable|boolean',
            'pan_number' => 'nullable|string|max:10',
            'aadhar_number' => 'nullable|string|max:12',
            'qualification' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:15',
            'local_address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'type_of_employment' => 'nullable|string|max:255',
            'identification_mark' => 'nullable|string|max:255',
            'pf_uan' => 'nullable|string|max:12',
            'esic_no' => 'nullable|string|max:17',
            'pwjby_policy' => 'nullable|string|max:255',
            'pmsby_start_date' => 'nullable|date',
            'pmsby_insurance' => 'nullable|string|max:255',
            'agency_number' => 'nullable|string|max:255',
            'bank_ifsc' => 'nullable|string|max:11',
            'bank_account' => 'nullable|string|max:20',
            'medical_condition' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'nominee_name' => 'nullable|string|max:255',
            'nominee_address' => 'nullable|string',
            'nominee_relation' => 'nullable|string|max:255',
            'nominee_phone' => 'nullable|string|max:15',
        ]);

        $workman = Workman::create($validated);

        // Log the activity
        ActivityLog::create([
            'action' => 'New Workman Added',
            'details' => "{$workman->name} {$workman->surname}",
            'user' => 'Admin',
        ]);

        return redirect()->route('new-workmen')->with('success', 'Workman added successfully!');
    }

    /**
     * Show the form for editing the specified workman.
     *
     * @param  \App\Models\Workman  $workman
     * @return \Illuminate\View\View
     */
    public function edit(Workman $workman)
    {
        $locations = Location::all();
        return view('workmen-edit', compact('workman', 'locations'));
    }

    /**
     * Update the specified workman in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Workman  $workman
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Workman $workman)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'sex' => 'nullable|in:male,female',
            'dob' => 'nullable|date',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'designation' => 'nullable|in:HSW,SSW,USW',
            'monthly_rate' => 'nullable|numeric|min:0',
            'handicapped' => 'nullable|boolean',
            'pan_number' => 'nullable|string|max:10',
            'aadhar_number' => 'nullable|string|max:12',
            'qualification' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:15',
            'local_address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'type_of_employment' => 'nullable|string|max:255',
            'identification_mark' => 'nullable|string|max:255',
            'pf_uan' => 'nullable|string|max:12',
            'esic_no' => 'nullable|string|max:17',
            'pwjby_policy' => 'nullable|string|max:255',
            'pmsby_start_date' => 'nullable|date',
            'pmsby_insurance' => 'nullable|string|max:255',
            'agency_number' => 'nullable|string|max:255',
            'bank_ifsc' => 'nullable|string|max:11',
            'bank_account' => 'nullable|string|max:20',
            'medical_condition' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'nominee_name' => 'nullable|string|max:255',
            'nominee_address' => 'nullable|string',
            'nominee_relation' => 'nullable|string|max:255',
            'nominee_phone' => 'nullable|string|max:15',
        ]);

        $workman->update($validated);

        // Log the activity
        ActivityLog::create([
            'action' => 'Workman Updated',
            'details' => "{$workman->name} {$workman->surname}",
            'user' => 'Admin',
        ]);

        return redirect()->route('workmen')->with('success', 'Workman updated successfully!');
    }

    /**
     * Remove the specified workman from storage.
     *
     * @param  \App\Models\Workman  $workman
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Workman $workman)
    {
        $workmanName = "{$workman->name} {$workman->surname}";
        $workman->delete();

        // Log the activity
        ActivityLog::create([
            'action' => 'Workman Deleted',
            'details' => $workmanName,
            'user' => 'Admin',
        ]);

        return redirect()->route('workmen')->with('success', 'Workman deleted successfully!');
    }

    /**
     * Download a PDF of the workman's details.
     *
     * @param  \App\Models\Workman  $workman
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf(Workman $workman)
    {
        $workman->load('location');
        $pdf = Pdf::loadView('workmen.pdf', compact('workman'));
        return $pdf->download("workman-details-{$workman->id}.pdf");
    }
}
