<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Workman;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        return view('HRAdmin.employee', compact('workmen', 'search'));
    }

    public function create()
    {
        $userEmail = Auth::user()->email;
        $locationId = Workman::select('location_id')->where('email', $userEmail)->first();
        $locations = Location::find($locationId);
        $designations = Designation::all();
        return view('HRAdmin.new-employee', compact('locations', 'designations'));
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
        $locations = Location::find($locationId);
        $designations = Designation::all();
        return view('HRAdmin.employee-edit', compact('employee', 'locations', 'designations'));
    }


    public function update(Request $request, Employee $workman)
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
        ]);

        try {
            // Process Base64 fields: Optionally strip data URI prefix to store only Base64 content
            $base64Fields = ['aadhar', 'pancard', 'bank_statement', 'passbook'];
            foreach ($base64Fields as $field) {
                if (isset($validated[$field]) && !empty($validated[$field])) {
                    // Optionally strip the data URI prefix (e.g., "data:image/jpeg;base64,")
                    $validated[$field] = preg_replace('/^data:image\/(jpeg|png|jpg);base64,/', '', $validated[$field]);
                }
            }

            // Sanitize empty strings to null
            $validated = array_map(function ($value) {
                return is_string($value) && trim($value) === '' ? null : $value;
            }, $validated);

            // Perform update and logging in a transaction
            DB::transaction(function () use ($validated, $workman) {
                $workman->update($validated);

                ActivityLog::create([
                    'action' => 'Employee Updated',
                    'details' => "{$workman->name} {$workman->surname}",
                    'user' => auth()->user()->name ?? 'System',
                ]);
            });

            return redirect()->route('employee.index')->with('success', 'Employee updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update employee: ' . $e->getMessage()]);
        }
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
