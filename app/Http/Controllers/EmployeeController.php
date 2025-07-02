<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Location;
use App\Models\SkillType;
use App\Models\Workman;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        if (Auth::user()->role == 'admin') {
            $workmen = Employee::with('location')
                ->when($search, function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('surname', 'like', "%{$search}%")
                        ->orWhere('designation', 'like', "%{$search}%")
                        ->orWhere('employee_unique_id', 'like', "%{$search}%")
                        ->orWhere('clims_id', 'like', "%{$search}%");
                })
                ->paginate(10);
        } else {
            $email = Auth::user()->email;
            $workman = Workman::where('email', $email)->first();
            $workmen = Employee::with('location')->where('location_id', $workman->location_id)
                ->when($search, function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('surname', 'like', "%{$search}%")
                        ->orWhere('designation', 'like', "%{$search}%")
                        ->orWhere('employee_unique_id', 'like', "%{$search}%")
                        ->orWhere('clims_id', 'like', "%{$search}%");
                })
                ->paginate(10);
        }


        return view('HRAdmin.employee', compact('workmen', 'search'));
    }

    public function create()
    {
        $userEmail = Auth::user()->email;
        $locationId = Workman::select('location_id')->where('email', $userEmail)->first();
        $locations = Location::find($locationId);
        $designations = Designation::all();
        $skills = SkillType::all();
        return view('HRAdmin.new-employee', compact('locations', 'designations','skills'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'sex' => 'nullable|in:male,female',
            'refer_by' => 'nullable|string',
            'dob' => 'nullable|date',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'designation' => 'integer|nullable',
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
            'aadhar' => 'nullable|string',
            'pancard' => 'nullable|string',
            'bank_statement' => 'nullable|string',
            'passbook' => 'nullable|string',
            'employee_unique_id' => 'nullable',
            // ✅ New allowance fields:
            'basic_pay' => 'nullable|numeric|min:0',
            'house_rent_allowance' => 'nullable|numeric|min:0',
            'conveyance_allowance' => 'nullable|numeric|min:0',
            'food_allowance' => 'nullable|numeric|min:0',
            'site_allowance' => 'nullable|numeric|min:0',
            'statutory_bonus' => 'nullable|numeric|min:0',
            'retrenchment_allowance' => 'nullable|numeric|min:0',
            'medical' => 'nullable|numeric|min:0',
            'clims_id' => 'nullable',
            'skill_type_id' => 'nullable',
            'bank_name' => 'nullable',
            'bank_location' => 'nullable',
            'bank_branch' => 'nullable',
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
        $userEmail = Auth::user()->email;
        $locationId = Workman::select('location_id')->where('email', $userEmail)->first();
        $locations = Location::all();
        $designations = Designation::all();
        $skills = SkillType::all();
        return view('HRAdmin.employee-edit', compact('employee', 'locations', 'designations','skills'));
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'sex' => 'nullable|in:male,female',
            'refer_by' => 'nullable|string',
            'dob' => 'nullable|date',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'designation' => 'integer|nullable',
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
            'aadhar' => 'nullable|string',
            'pancard' => 'nullable|string',
            'bank_statement' => 'nullable|string',
            'passbook' => 'nullable|string',
            'employee_unique_id' => 'nullable',
            // ✅ New allowance fields:
            'basic_pay' => 'nullable|numeric|min:0',
            'house_rent_allowance' => 'nullable|numeric|min:0',
            'conveyance_allowance' => 'nullable|numeric|min:0',
            'food_allowance' => 'nullable|numeric|min:0',
            'site_allowance' => 'nullable|numeric|min:0',
            'statutory_bonus' => 'nullable|numeric|min:0',
            'retrenchment_allowance' => 'nullable|numeric|min:0',
            'medical' => 'nullable|numeric|min:0',
            'clims_id' => 'nullable',
            'skill_type_id' => 'nullable',
            'bank_name' => 'nullable',
            'bank_location' => 'nullable',
            'bank_branch' => 'nullable',
        ]);


        $workman = Employee::find($id);
        $workman->update($validated);



        // Log the activity
        ActivityLog::create([
            'action' => 'Employee Updated',
            'details' => "{$workman->name} {$workman->surname}",
            'user' => 'Admin',
        ]);

        return redirect()->route('employee.index')->with('success', 'Employee updated successfully!');
    }


    public function destroy(Employee $employee)
    {
        $workmanName = "{$employee->name} {$employee->surname}";
        $employee->delete();

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
        $pdf = Pdf::loadView('HRAdmin.employee.pdf', compact('employee'));
        return $pdf->download("workman-details-{$employee->id}.pdf");
    }
}
