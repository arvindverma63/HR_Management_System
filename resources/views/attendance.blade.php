<!DOCTYPE html>
<html lang="en">
@include('partials.head')

<body class="bg-gradient-to-br from-gray-50 to-gray-200 font-sans antialiased">
    <div class="flex min-h-screen">
        @include('partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 p-4 md:p-8 w-full">
            <!-- Mobile Menu Button -->
            <button id="open-sidebar"
                class="md:hidden fixed top-4 left-4 z-30 bg-custom-blue text-white p-2 rounded-lg focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            @include('partials.header')

            <!-- Take Attendance Section -->
            <section id="take-attendance">
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg">
                    <h3 class="text-xl md:text-2xl font-semibold text-gray-800 mb-4 md:mb-6">Take Attendance</h3>

                    <!-- Success/Error Messages -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="GET" action="{{ route('attendence') }}"
                        class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 md:mb-6 space-y-4 md:space-y-0">
                        <div class="w-full md:w-auto">
                            <label for="attendance-date" class="block text-sm font-medium text-gray-700">Select
                                Date</label>
                            <input type="date" id="attendance-date" name="date" value="{{ $date }}"
                                class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                onchange="this.form.submit()">
                        </div>
                        <div class="mb-4">
                            <label for="location_id" class="block text-sm font-medium text-gray-700">Location</label>
                            <select id="location_id" name="location_id"
                                class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                onchange="this.form.submit()">
                                <option value="">All Locations</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}"
                                        {{ $location_id == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full md:w-auto">
                            <input type="text" id="search" name="search" value="{{ $search }}"
                                class="w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                placeholder="Search employees..." oninput="this.form.submit()">
                        </div>
                    </form>

                    <form method="POST" action="{{ route('attendence.store') }}">
                        @csrf
                        <input type="hidden" name="attendance_date" value="{{ $date }}">

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm md:text-base">
                                <thead>
                                    <tr class="bg-custom-blue text-white">
                                        <th class="p-2 md:p-4 cursor-pointer hover:bg-custom-blue-dark transition-all"
                                            onclick="sortTable(0)">Employee Name</th>
                                        <th class="p-2 md:p-4 cursor-pointer hover:bg-custom-blue-dark transition-all"
                                            onclick="sortTable(1)">Department</th>
                                        <th class="p-2 md:p-4">Status</th>
                                        <th class="p-2 md:p-4">Notes</th>
                                    </tr>
                                </thead>
                                <tbody id="attendance-table">
                                    @forelse ($workmen as $workman)
                                        <tr class="border-b hover:bg-gray-50 transition-all">
                                            <td class="p-2 md:p-4">{{ $workman->name }} {{ $workman->surname }}</td>
                                            <td class="p-2 md:p-4">{{ $workman->designation ?? 'N/A' }}</td>
                                            <td class="p-2 md:p-4">
                                                <select name="attendance[{{ $workman->id }}][status]"
                                                    class="p-1 md:p-2 border rounded-lg focus:ring-2 focus:ring-custom-blue transition-all w-full"
                                                    {{ $attendanceExists ? 'disabled' : '' }}>
                                                    <option value="present">Present</option>
                                                    <option value="absent">Absent</option>
                                                    <option value="leave">Leave</option>
                                                </select>
                                                <input type="hidden"
                                                    name="attendance[{{ $workman->id }}][workman_id]"
                                                    value="{{ $workman->id }}">
                                                <input type="hidden"
                                                    name="attendance[{{ $workman->id }}][location_id]"
                                                    value="{{ $workman->location_id }}">
                                            </td>
                                            <td class="p-2 md:p-4">
                                                <input type="text"
                                                    name="attendance[{{ $workman->id }}][overtime_hours]"
                                                    class="p-1 md:p-2 border rounded-lg w-full focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                                    placeholder="overtime_hours"
                                                    {{ $attendanceExists ? 'disabled' : '' }}>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="p-2 md:p-4 text-center text-gray-500">No employees
                                                found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div
                            class="flex flex-col md:flex-row justify-between items-start md:items-center mt-4 md:mt-6 space-y-4 md:space-y-0">
                            <div>
                                <span class="text-gray-600 text-sm md:text-base">Showing {{ $workmen->firstItem() }} to
                                    {{ $workmen->lastItem() }} of {{ $workmen->total() }} entries</span>
                            </div>
                            <div class="flex space-x-2">
                                {{ $workmen->appends(['date' => $date, 'search' => $search, 'location_id' => $location_id])->links('pagination::tailwind') }}
                            </div>
                        </div>

                        <button type="submit"
                            class="mt-4 md:mt-6 w-full bg-custom-blue text-white py-2 md:py-3 rounded-lg hover:bg-custom-blue-dark transition-all"
                            {{ $attendanceExists ? 'disabled' : '' }}>
                            Submit Attendance
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </div>

    @include('partials.js')

    <script>
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
