<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Employee;
use App\Models\Location;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $workmen = Employee::with('location')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('surname', 'like', "%{$search}%")
                      ->orWhere('designation', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('hradmin.employee', compact('workmen', 'search'));
    }

    public function create()
    {
        $locations = Location::all();
        return view('HRAdmin.new-employee', compact('locations'));
    }

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
            'hourly_pay' => 'nullable|numeric',
        ]);

        $workman = Employee::create($validated);

        // Log the activity
        ActivityLog::create([
            'action' => 'New Employee Added',
            'details' => "{$workman->name} {$workman->surname}",
            'user' => 'HR',
        ]);

        return redirect()->route('employee.index')->with('success', 'Employee added successfully!');
    }


    public function edit(Employee $employee)
    {
        $locations = Location::all();
        return view('HRAdmin.employee-edit', compact('employee', 'locations'));
    }


    public function update(Request $request, Employee $workman)
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
            'hourly_pay' => 'nullable|numeric',
        ]);

        $workman->update($validated);

        // Log the activity
        ActivityLog::create([
            'action' => 'Employee Updated',
            'details' => "{$workman->name} {$workman->surname}",
            'user' => 'Admin',
        ]);

        return redirect()->route('HRAdmin.employee')->with('success', 'Employee updated successfully!');
    }


    public function destroy(Employee $workman)
    {
        $workmanName = "{$workman->name} {$workman->surname}";
        $workman->delete();

        // Log the activity
        ActivityLog::create([
            'action' => 'Employee Deleted',
            'details' => $workmanName,
            'user' => 'Admin',
        ]);

        return redirect()->route('employee.index')->with('success', 'Employee deleted successfully!');
    }


    public function downloadPdf(Employee $employee)
    {
        $employee->load('location');
        $pdf = Pdf::loadView('employee.pdf', compact('employee'));
        return $pdf->download("workman-details-{$employee->id}.pdf");
    }
}
