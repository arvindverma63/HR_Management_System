<!DOCTYPE html>
<html>
<head>
    <title>Workman Details - {{ $workman->name }} {{ $workman->surname }}</title>
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
            <td>{{ $workman->name }} {{ $workman->surname }}</td>
        </tr>
        <tr>
            <th>Location</th>
            <td>{{ $workman->location->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Department</th>
            <td>{{ $workman->designation ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Sex</th>
            <td>{{ $workman->sex ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Date of Birth</th>
            <td>{{ $workman->dob ? $workman->dob->format('Y-m-d') : 'N/A' }}</td>
        </tr>
        <tr>
            <th>Blood Group</th>
            <td>{{ $workman->blood_group ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Monthly Rate</th>
            <td>{{ $workman->monthly_rate ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Handicapped</th>
            <td>{{ $workman->handicapped ? 'Yes' : 'No' }}</td>
        </tr>
        <tr>
            <th>PAN Number</th>
            <td>{{ $workman->pan_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Aadhar Number</th>
            <td>{{ $workman->aadhar_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Qualification</th>
            <td>{{ $workman->qualification ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Mobile Number</th>
            <td>{{ $workman->mobile_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Local Address</th>
            <td>{{ $workman->local_address ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Emergency Contact</th>
            <td>{{ $workman->emergency_contact ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Father's Name</th>
            <td>{{ $workman->father_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Permanent Address</th>
            <td>{{ $workman->permanent_address ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>City</th>
            <td>{{ $workman->city ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>State</th>
            <td>{{ $workman->state ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Phone</th>
            <td>{{ $workman->phone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Type of Employment</th>
            <td>{{ $workman->type_of_employment ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Identification Mark</th>
            <td>{{ $workman->identification_mark ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>PF UAN</th>
            <td>{{ $workman->pf_uan ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>ESIC No</th>
            <td>{{ $workman->esic_no ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>PWJBY Policy</th>
            <td>{{ $workman->pwjby_policy ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>PMSBY Start Date</th>
            <td>{{ $workman->pmsby_start_date ? $workman->pmsby_start_date->format('Y-m-d') : 'N/A' }}</td>
        </tr>
        <tr>
            <th>PMSBY Insurance</th>
            <td>{{ $workman->pmsby_insurance ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Agency Number</th>
            <td>{{ $workman->agency_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Bank IFSC</th>
            <td>{{ $workman->bank_ifsc ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Bank Account</th>
            <td>{{ $workman->bank_account ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Medical Condition</th>
            <td>{{ $workman->medical_condition ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Nationality</th>
            <td>{{ $workman->nationality ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Nominee Name</th>
            <td>{{ $workman->nominee_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Nominee Address</th>
            <td>{{ $workman->nominee_address ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Nominee Relation</th>
            <td>{{ $workman->nominee_relation ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Nominee Phone</th>
            <td>{{ $workman->nominee_phone ?? 'N/A' }}</td>
        </tr>
    </table>
</body>
</html>
