<!DOCTYPE html>
<html lang="en">
@include('partials.head')

<body class="bg-gradient-to-br from-gray-50 to-gray-200 font-sans antialiased">
    <div class="flex min-h-screen">
        @include('HRAdmin.partials.sidebar')

        <!-- Main Content -->
        <main class="flex-1 p-3 md:p-4 w-full">
            <!-- Mobile Menu Button -->
            <button id="open-sidebar"
                class="md:hidden fixed top-2 left-2 z-30 bg-blue-600 text-white p-1.5 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                aria-label="Open sidebar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            @include('partials.header')

            <!-- Take Attendance Section -->
            <section id="take-attendance" class="bg-white p-4 md:p-6 rounded-xl shadow-md">
                <h3 class="text-lg md:text-xl font-semibold text-gray-800 mb-4">Take Attendance</h3>

                <!-- Messages -->
                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md text-sm" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm" role="alert">
                        <ul class="list-disc pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Search and Date Filter Form -->
                <form method="GET" action="{{ route('EmployeeAttendence') }}"
                    class="flex flex-col sm:flex-row gap-3 mb-4">
                    <div class="w-full sm:w-1/2">
                        <label for="attendance-date" class="block text-xs font-medium text-gray-700 mb-1">Select
                            Date</label>
                        <input type="date" id="attendance-date" name="date" value="{{ $date }}"
                            class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm"
                            aria-label="Select attendance date" onchange="this.form.submit()">
                    </div>
                    <div class="w-full sm:w-1/2">
                        <label for="search" class="block text-xs font-medium text-gray-700 mb-1">Search
                            Employees</label>
                        <input type="text" id="search" name="search" value="{{ $search }}"
                            class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm"
                            placeholder="Search by name..." aria-label="Search employees" oninput="this.form.submit()">
                    </div>
                </form>

                <!-- Note about Overtime -->
                <p class="text-xs text-gray-600 mb-3">Note: Overtime hours will be recorded for the next day.</p>

                <!-- Attendance Form -->
                <form method="POST" action="{{ route('EmployeeAttendence.store') }}">
                    @csrf
                    <input type="hidden" name="attendance_date" value="{{ $date }}">

                    <!-- Calculate Sundays in the Month -->
                    @php
                        $selectedDate = \Carbon\Carbon::parse($date);
                        $monthStart = $selectedDate->copy()->startOfMonth();
                        $monthEnd = $selectedDate->copy()->endOfMonth();
                        $sundays = [];
                        for ($day = $monthStart; $day <= $monthEnd; $day->addDay()) {
                            if ($day->isSunday()) {
                                $sundays[] = $day->toDateString();
                            }
                        }
                    @endphp

                    <!-- Attendance Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs md:text-sm">
                            <thead>
                                <tr class="bg-blue-600 text-white">
                                    <th class="p-2 md:p-3 cursor-pointer hover:bg-blue-700 transition-all"
                                        onclick="sortTable(0)">Employee Name</th>
                                    <th class="p-2 md:p-3">FormId</th>
                                    <th class="p-2 md:p-3">ClimId</th>
                                    <th class="p-2 md:p-3 cursor-pointer hover:bg-blue-700 transition-all"
                                        onclick="sortTable(1)">Department</th>
                                    <th class="p-2 md:p-3">Status</th>
                                    <th class="p-2 md:p-3">Overtime (Hrs)</th>
                                    <th class="p-2 md:p-3">Sundays (@foreach ($sundays as $sunday)
                                            {{ \Carbon\Carbon::parse($sunday)->format('M d') }}
                                        @endforeach)</th>
                                </tr>
                            </thead>
                            <tbody id="attendance-table">
                                @forelse ($workmen as $workman)
                                    <tr class="border-b hover:bg-gray-50 transition-all">
                                        <td class="p-2 md:p-3">{{ $workman->name }} {{ $workman->surname }}</td>
                                        <td class="p-2 md:p-3">{{ $workman->employee_unique_id }} </td>
                                        <td class="p-2 md:p-3">{{ $workman->clim_id }} </td>
                                        <td class="p-2 md:p-3">{{ $workman->designation ?? 'N/A' }}</td>
                                        <td class="p-2 md:p-3">
                                            <select name="attendance[{{ $workman->id }}][status]"
                                                class="p-1 border rounded-md focus:ring-2 focus:ring-blue-500 transition-all w-full text-sm"
                                                {{ isset($existingAttendance) && in_array($workman->id, $existingAttendance) ? 'disabled' : '' }}
                                                aria-label="Attendance status for {{ $workman->name }} {{ $workman->surname }}">
                                                <option value="absent">Absent</option>
                                                <option value="present">Present</option>
                                                <option value="leave">Leave</option>
                                            </select>
                                            <input type="hidden" name="attendance[{{ $workman->id }}][employee_id]"
                                                value="{{ $workman->id }}">
                                            <input type="hidden" name="attendance[{{ $workman->id }}][location_id]"
                                                value="{{ $workman->location_id }}">
                                            <input type="hidden" name="attendance[{{ $workman->id }}][notes]"
                                                value="">
                                        </td>
                                        <td class="p-2 md:p-3">
                                            <input type="number"
                                                name="attendance[{{ $workman->id }}][overtime_hours]"
                                                class="p-1 border rounded-md w-full focus:ring-2 focus:ring-blue-500 transition-all text-sm"
                                                placeholder="0" step="0.5" min="0" max="500"
                                                {{ isset($existingAttendance) && in_array($workman->id, $existingAttendance) ? 'disabled' : '' }}
                                                aria-label="Overtime hours for {{ $workman->name }} {{ $workman->surname }}">
                                        </td>
                                        <td class="p-2 md:p-3 flex gap-2 flex-wrap">
                                            @foreach ($sundays as $sunday)
                                                <div class="flex items-center">
                                                    <input type="checkbox"
                                                        name="attendance[{{ $workman->id }}][sunday_attendance][{{ $sunday }}]"
                                                        value="present"
                                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                        {{ isset($existingAttendance) && in_array($workman->id, $existingAttendance) ? 'disabled' : '' }}
                                                        {{ isset($workman->sunday_attendance[$sunday]) && $workman->sunday_attendance[$sunday] == 'present' ? 'checked' : '' }}
                                                        aria-label="Sunday attendance for {{ $workman->name }} {{ $workman->surname }} on {{ $sunday }}">
                                                    <label
                                                        class="text-xs text-gray-600 ml-1">{{ \Carbon\Carbon::parse($sunday)->format('M d') }}</label>
                                                </div>
                                            @endforeach
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-2 md:p-3 text-center text-gray-500 text-sm">No
                                            employees found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mt-4 gap-3">
                        <span class="text-gray-600 text-xs">
                            Showing {{ $workmen->firstItem() }} to {{ $workmen->lastItem() }} of
                            {{ $workmen->total() }} entries
                        </span>
                        <div class="flex space-x-1">
                            {{ $workmen->appends(['date' => $date, 'search' => $search])->links('pagination::tailwind') }}
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="mt-4 w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition-all text-sm"
                        aria-label="Submit attendance records">
                        Submit Attendance
                    </button>
                </form>
            </section>
        </main>
    </div>

    @include('partials.js')

    <script>
        // Sort table by column index
        function sortTable(columnIndex) {
            const table = document.getElementById('attendance-table');
            const rows = Array.from(table.rows);
            const isAscending = table.dataset.sortDirection !== 'asc';
            table.dataset.sortDirection = isAscending ? 'asc' : 'desc';

            rows.sort((a, b) => {
                const aValue = a.cells[columnIndex].textContent.trim();
                const bValue = b.cells[columnIndex].textContent.trim();
                return isAscending ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
            });

            rows.forEach(row => table.appendChild(row));
        }
    </script>
</body>

</html>
