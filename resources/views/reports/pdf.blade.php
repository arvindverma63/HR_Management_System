<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report - {{ $date }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; color: #1E40AF; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #1E40AF; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Attendance Report for {{ $date }}</h1>
    <table>
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Department</th>
                <th>Status</th>
                <th>Notes</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->workman->name }} {{ $attendance->workman->surname }}</td>
                    <td>{{ $attendance->workman->designation ?? 'N/A' }}</td>
                    <td>{{ $attendance->status }}</td>
                    <td>{{ $attendance->notes ?? '' }}</td>
                    <td>{{ $attendance->attendance_date->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No attendance records found for this date.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
