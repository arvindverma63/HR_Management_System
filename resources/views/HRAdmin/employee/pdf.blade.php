<!DOCTYPE html>
<html>
<head>
    <title>Employee Details - {{ $employee->name }} {{ $employee->surname }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; color: #1E40AF; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #1E40AF; color: white; width: 30%; }
        tr:nth-child(even) { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Workman Details</h1>
    <table>
        <tr>
            <th>Name</th>
            <td>{{ $employee->name }} {{ $employee->surname }}</td>
        </tr>
        <tr>
            <th>Location</th>
            <td>{{ $employee->location->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Department</th>
            <td>{{ $employee->designation ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Sex</th>
            <td>{{ $employee->sex ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Date of Birth</th>
            <td>{{ $employee->dob ? $employee->dob->format('Y-m-d') : 'N/A' }}</td>
        </tr>
        <tr>
            <th>Blood Group</th>
            <td>{{ $employee->blood_group ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Monthly Rate</th>
            <td>{{ $employee->monthly_rate ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Handicapped</th>
            <td>{{ $employee->handicapped ? 'Yes' : 'No' }}</td>
        </tr>
        <tr>
            <th>PAN Number</th>
            <td>{{ $employee->pan_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Aadhar Number</th>
            <td>{{ $employee->aadhar_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Qualification</th>
            <td>{{ $employee->qualification ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Mobile Number</th>
            <td>{{ $employee->mobile_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Local Address</th>
            <td>{{ $employee->local_address ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Emergency Contact</th>
            <td>{{ $employee->emergency_contact ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Father's Name</th>
            <td>{{ $employee->father_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Permanent Address</th>
            <td>{{ $employee->permanent_address ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>City</th>
            <td>{{ $employee->city ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>State</th>
            <td>{{ $employee->state ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Phone</th>
            <td>{{ $employee->phone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Type of Employment</th>
            <td>{{ $employee->type_of_employment ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Identification Mark</th>
            <td>{{ $employee->identification_mark ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>PF UAN</th>
            <td>{{ $employee->pf_uan ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>ESIC No</th>
            <td>{{ $employee->esic_no ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>PWJBY Policy</th>
            <td>{{ $employee->pwjby_policy ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>PMSBY Start Date</th>
            <td>{{ $employee->pmsby_start_date ? $employee->pmsby_start_date->format('Y-m-d') : 'N/A' }}</td>
        </tr>
        <tr>
            <th>PMSBY Insurance</th>
            <td>{{ $employee->pmsby_insurance ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Agency Number</th>
            <td>{{ $employee->agency_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Bank IFSC</th>
            <td>{{ $employee->bank_ifsc ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Bank Account</th>
            <td>{{ $employee->bank_account ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Medical Condition</th>
            <td>{{ $employee->medical_condition ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Nationality</th>
            <td>{{ $employee->nationality ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Nominee Name</th>
            <td>{{ $employee->nominee_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Nominee Address</th>
            <td>{{ $employee->nominee_address ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Nominee Relation</th>
            <td>{{ $employee->nominee_relation ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Nominee Phone</th>
            <td>{{ $employee->nominee_phone ?? 'N/A' }}</td>
        </tr>
    </table>
</body>
</html>
