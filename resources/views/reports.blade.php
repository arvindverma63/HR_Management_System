
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

            <!-- Reports Section -->
            <section id="reports">
                <div class="space-y-8">
                    <!-- Date Filter -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 md:mb-6 space-y-4 md:space-y-0">
                        <form method="GET" action="{{ route('reports') }}" class="w-full md:w-auto">
                            <div>
                                <label for="report-date" class="block text-sm font-medium text-gray-700">Select Date</label>
                                <input type="date" id="report-date" name="date" value="{{ $date }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    onchange="this.form.submit()">
                            </div>
                        </form>
                        <div class="flex space-x-4 w-full md:w-auto">
                            <form method="GET" action="{{ route('reports.download-pdf') }}">
                                <input type="hidden" name="date" value="{{ $date }}">
                                <button id="download-pdf"
                                    class="w-full md:w-auto px-4 py-2 bg-custom-blue text-white rounded-lg hover:bg-custom-blue-dark transition-all">
                                    Download PDF
                                </button>
                            </form>
                            <form method="GET" action="{{ route('reports.download-csv') }}">
                                <input type="hidden" name="date" value="{{ $date }}">
                                <button id="download-csv"
                                    class="w-full md:w-auto px-4 py-2 bg-custom-blue text-white rounded-lg hover:bg-custom-blue-dark transition-all">
                                    Download CSV
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Graphs -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Bar Chart: Attendance Status -->
                        <div class="bg-white p-6 rounded-2xl shadow-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Attendance Status</h3>
                            <canvas id="attendanceStatusChart" height="200"></canvas>
                        </div>
                        <!-- Pie Chart: Department Distribution -->
                        <div class="bg-white p-6 rounded-2xl shadow-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Department Distribution</h3>
                            <canvas id="departmentChart" height="200"></canvas>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="bg-white p-6 rounded-2xl shadow-lg" id="report-table">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Attendance Records</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm md:text-base">
                                <thead>
                                    <tr class="bg-custom-blue text-white">
                                        <th class="p-2 md:p-4">Employee Name</th>
                                        <th class="p-2 md:p-4">Department</th>
                                        <th class="p-2 md:p-4">Status</th>
                                        <th class="p-2 md:p-4">Notes</th>
                                        <th class="p-2 md:p-4">Date</th>
                                    </tr>
                                </thead>
                                <tbody id="attendance-records">
                                    @forelse ($attendances as $attendance)
                                        <tr class="border-b hover:bg-gray-50 transition-all">
                                            <td class="p-2 md:p-4">{{ $attendance->workman->name }} {{ $attendance->workman->surname }}</td>
                                            <td class="p-2 md:p-4">{{ $attendance->workman->designation ?? 'N/A' }}</td>
                                            <td class="p-2 md:p-4">{{ ucfirst($attendance->status) }}</td>
                                            <td class="p-2 md:p-4">{{ $attendance->notes ?? '' }}</td>
                                            <td class="p-2 md:p-4">{{ $attendance->attendance_date->format('Y-m-d') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="p-2 md:p-4 text-center text-gray-500">No attendance records found for this date.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Attendance Status Chart (Bar Chart)
        const attendanceCtx = document.getElementById('attendanceStatusChart').getContext('2d');
        new Chart(attendanceCtx, {
            type: 'bar',
            data: {
                labels: @json($attendanceChartData['labels']),
                datasets: [{
                    label: 'Attendance',
                    data: @json($attendanceChartData['data']),
                    backgroundColor: ['#22C55E', '#EF4444', '#F59E0B'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Department Distribution Chart (Pie Chart)
        const departmentCtx = document.getElementById('departmentChart').getContext('2d');
        new Chart(departmentCtx, {
            type: 'pie',
            data: {
                labels: @json($departmentChartData['labels']),
                datasets: [{
                    label: 'Departments',
                    data: @json($departmentChartData['data']),
                    backgroundColor: ['#3B82F6', '#10B981', '#F59E0B'],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>

    @include('partials.js')
</body>
</html>
