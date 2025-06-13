<?php

namespace App\Http\Controllers;

use App\Models\Workman;
use App\Models\Location;
use App\Models\ActivityLog;
use App\Models\Designation;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;

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
        $designations = Designation::all();
        return view('new-workmen', compact('locations', 'designations'));
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
            'designation' => 'required',
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
            'hourly_pay' => 'nullable|numeric',
            'email' => 'required|email',
            'aadhar' => 'nullable|string',
            'pancard' => 'nullable|string',
            'bank_statement' => 'nullable|string',
            'passbook' => 'nullable|string',
            'da'=>'nullable',
            'workman_unique_id' => 'nullable'
        ]);

        $workman = Workman::create($validated);
        if ($workman) {
            User::create([
                'name' => $workman->name,
                'email' => $workman->email,
                'password' => Hash::make($workman->mobile_number),
                'role' => 'hr',
            ]);
        }

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
        $designations = Designation::all();
        return view('workmen-edit', compact('workman', 'locations', 'designations'));
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
            'designation' => 'nullable',
            'monthly_rate' => 'nullable|numeric|min:0',
            'handicapped' => 'nullable|boolean',
            'workman_unique_id' => 'nullable',
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
            'hourly_pay' => 'nullable|numeric',
            'email' => 'required|email',
            'aadhar' => 'nullable|string',
            'pancard' => 'nullable|string',
            'bank_statement' => 'nullable|string',
            'passbook' => 'nullable|string',
            'da'=>'nullable',
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

        if ($workman) {
            $user = User::where('email', $workman->email)->first();
            $user->delete();
        }

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
