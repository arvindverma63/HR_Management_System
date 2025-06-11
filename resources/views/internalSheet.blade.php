<!DOCTYPE html>
<html lang="en">
@include('partials.head')

<!-- Include Vanilla DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanilla-datatables@latest/dist/vanilla-dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/vanilla-datatables@latest/dist/vanilla-dataTables.min.js"></script>

<body class="bg-gradient-to-br from-gray-100 to-gray-300 font-sans antialiased">
    <div class="flex min-h-screen flex-col md:flex-row">
        @include('partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 w-full">
            <!-- Mobile Menu Button -->
            <button id="open-sidebar"
                class="md:hidden fixed top-4 left-4 z-30 bg-[#134a6b] text-white p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#134a6b] transition"
                aria-label="Open sidebar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            @include('partials.header')

            <!-- HR Report Content -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mt-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6">HR Report</h2>

                <!-- Form for Month and Year -->
                <form method="POST" action="{{ route('hr-report') }}"
                    class="flex flex-col sm:flex-row gap-3 sm:gap-4 mb-4 sm:mb-6">
                    @csrf
                    <div class="flex-1">
                        <label for="month" class="block text-xs sm:text-sm font-medium text-gray-700">Month</label>
                        <select id="month" name="month"
                            class="mt-1 block w-full rounded-md border-gray-300 text-sm sm:text-base shadow-sm focus:border-[#134a6b] focus:ring focus:ring-[#134a6b] focus:ring-opacity-50">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}"
                                    {{ old('month', date('n')) == $i ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                            @endfor
                        </select>
                        @error('month')
                            <span class="text-red-500 text-xs sm:text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex-1">
                        <label for="year" class="block text-xs sm:text-sm font-medium text-gray-700">Year</label>
                        <input type="number" id="year" name="year" value="{{ old('year', date('Y')) }}"
                            min="1900" max="9999"
                            class="mt-1 block w-full rounded-md border-gray-300 text-sm sm:text-base shadow-sm focus:border-[#134a6b] focus:ring focus:ring-[#134a6b] focus:ring-opacity-50">
                        @error('year')
                            <span class="text-red-500 text-xs sm:text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="bg-[#134a6b] text-white px-3 py-2 sm:px-4 sm:py-2 rounded-md text-sm sm:text-base hover:bg-[#0f3a54] transition">Generate
                            Report</button>
                    </div>
                </form>

                <!-- Report Table -->
                @if (isset($report))
                    <div class="overflow-x-auto relative">
                        <table id="myTable"
                            class="min-w-full table-auto text-sm text-left text-gray-700 border border-gray-200 responsive-table">
                            <thead class="bg-gray-100 text-xs uppercase font-semibold text-gray-600">
                                <tr>
                                    <th
                                        class="sticky left-0 bg-gray-50 px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider z-10">
                                        Name
                                    </th>
                                    <th
                                        class="sticky left-[100px] bg-gray-50 px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider z-10">
                                        Rate/Month
                                    </th>
                                    <th
                                        class="hidden sm:table-cell px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Wages
                                    </th>
                                    <th
                                        class="hidden md:table-cell px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        OT Rate
                                    </th>
                                    <th
                                        class="sticky left-[200px] bg-gray-50 px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider z-10">
                                        Days
                                    </th>
                                    <th
                                        class="px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        OT Hours
                                    </th>
                                    <th class="sticky left-[300px] bg-gray-50 px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider z-10"
                                        colspan="3">
                                        Earnings
                                    </th>
                                    <th class="sticky left-[450px] bg-gray-50 px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider z-10"
                                        colspan="4">
                                        Deductions
                                    </th>
                                    <th
                                        class="sticky right-0 bg-gray-50 px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider z-10">
                                        Net Pay
                                    </th>
                                </tr>
                                <tr>
                                    <th class="sticky left-0 bg-gray-50 px-2 sm:px-4 py-2 sm:py-3 z-10"></th>
                                    <th class="sticky left-[100px] bg-gray-50 px-2 sm:px-4 py-2 sm:py-3 z-10"></th>
                                    <th class="hidden sm:table-cell px-2 sm:px-4 py-2 sm:py-3"></th>
                                    <th class="hidden md:table-cell px-2 sm:px-4 py-2 sm:py-3"></th>
                                    <th class="sticky left-[200px] bg-gray-50 px-2 sm:px-4 py-2 sm:py-3 z-10"></th>
                                    <th class="px-2 sm:px-4 py-2 sm:py-3"></th>
                                    <th class="sticky left-[300px] bg-gray-50 px-2 sm:px-4 py-2 sm:py-3 z-10">Basic</th>
                                    <th class="hidden sm:table-cell px-2 sm:px-4 py-2 sm:py-3">Overtime</th>
                                    <th class="hidden md:table-cell px-2 sm:px-4 py-2 sm:py-3">Others</th>
                                    <th class="sticky left-[450px] bg-gray-50 px-2 sm:px-4 py-2 sm:py-3 z-10">Cash</th>
                                    <th class="hidden sm:table-cell px-2 sm:px-4 py-2 sm:py-3">Misc</th>
                                    <th class="hidden md:table-cell px-2 sm:px-4 py-2 sm:py-3">Bank Adv</th>
                                    <th class="hidden lg:table-cell px-2 sm:px-4 py-2 sm:py-3">Total</th>
                                    <th class="sticky right-0 bg-gray-50 px-2 sm:px-4 py-2 sm:py-3 z-10"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($report as $row)
                                    <tr>
                                        <td class="sticky left-0 bg-white px-2 sm:px-4 py-2 sm:py-4 text-gray-900 z-5">
                                            {{ $row->name }}</td>
                                        <td
                                            class="sticky left-[100px] bg-white px-2 sm:px-4 py-2 sm:py-4 text-gray-900 z-5">
                                            {{ number_format($row->rate_per_month, 2) }}</td>
                                        <td class="hidden sm:table-cell px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ number_format($row->rate_of_wages, 2) }}</td>
                                        <td class="hidden md:table-cell px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ number_format($row->rate_of_ot, 2) }}</td>
                                        <td
                                            class="sticky left-[200px] bg-white px-2 sm:px-4 py-2 sm:py-4 text-gray-900 z-5">
                                            {{ $row->days_worked }}</td>
                                        <td class="px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ number_format($row->overtime_hours, 2) }}</td>
                                        <td
                                            class="sticky left-[300px] bg-white px-2 sm:px-4 py-2 sm:py-4 text-gray-900 z-5">
                                            {{ number_format($row->basic_earnings, 2) }}</td>
                                        <td class="hidden sm:table-cell px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ number_format($row->overtime_earnings, 2) }}</td>
                                        <td class="hidden md:table-cell px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ number_format($row->other_earnings, 2) }}</td>
                                        <td
                                            class="sticky left-[450px] bg-white px-2 sm:px-4 py-2 sm:py-4 text-gray-900 z-5">
                                            {{ number_format($row->cash_deduction, 2) }}</td>
                                        <td class="hidden sm:table-cell px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ number_format($row->misc_recovery, 2) }}</td>
                                        <td class="hidden md:table-cell px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ number_format($row->bank_adv, 2) }}</td>
                                        <td class="hidden lg:table-cell px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ number_format($row->total_deduction, 2) }}</td>
                                        <td class="sticky right-0 bg-white px-2 sm:px-4 py-2 sm:py-4 text-gray-900 z-5">
                                            {{ number_format($row->net_payments, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif (isset($report))
                    <p class="text-gray-600 text-sm sm:text-base">No data available for the selected month and year.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .responsive-table {
            border-collapse: collapse;
            width: 100%;
        }

        .responsive-table th,
        .responsive-table td {
            position: relative;
            white-space: nowrap;
        }

        /* Sticky headers and columns */
        .responsive-table th.sticky,
        .responsive-table td.sticky {
            z-index: 5;
            box-shadow: 1px 0 2px rgba(0, 0, 0, 0.1);
        }

        .responsive-table th.sticky.right-0,
        .responsive-table td.sticky.right-0 {
            right: 0;
            box-shadow: -1px 0 2px rgba(0, 0, 0, 0.1);
        }

        /* DataTables styling */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem;
            margin-left: 0.5rem;
        }

        .dataTables_wrapper .dataTables_paginate {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background: #134a6b;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            background: #e5e7eb;
            color: #6b7280;
            cursor: not-allowed;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) {
            background: #0f3a54;
        }

        .dataTables_wrapper .dataTables_info {
            margin-top: 1rem;
            color: #6b7280;
        }

        /* Custom scrollbar */
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #134a6b #e5e7eb;
        }

        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #e5e7eb;
            border-radius: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #134a6b;
            border-radius: 4px;
        }

        /* Responsive adjustments */
        @media (max-width: 640px) {

            .responsive-table th:not(.sticky),
            .responsive-table td:not(.sticky) {
                display: none;
            }

            .responsive-table th.sticky,
            .responsive-table td.sticky {
                min-width: 100px;
            }

            .responsive-table th.sticky.left-[100px],
            .responsive-table td.sticky.left-[100px],
            .responsive-table th.sticky.left-[200px],
            .responsive-table td.sticky.left-[200px],
            .responsive-table th.sticky.left-[300px],
            .responsive-table td.sticky.left-[300px],
            .responsive-table th.sticky.left-[450px],
            .responsive-table td.sticky.left-[450px] {
                left: 100px;
            }
        }

        @media (min-width: 641px) and (max-width: 767px) {

            .responsive-table th.hidden.sm,
            .responsive-table td.hidden.sm {
                display: table-cell;
            }
        }
    </style>

    <!-- Custom JS -->
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', () => {
            const table = document.querySelector('#myTable');
            const dataTable = new DataTable(table, {
                searchable: true,
                sortable: true,
                perPage: 10,
                perPageSelect: [5, 10, 20, 50],
                fixedColumns: {
                    leftColumns: 3, // Name, Rate/Month, Days
                    rightColumns: 1 // Net Pay
                },
                labels: {
                    placeholder: "Search...",
                    perPage: "{select} entries per page",
                    noRows: "No entries found",
                    info: "Showing {start} to {end} of {rows} entries"
                }
            });

            // Handle column visibility based on screen size
            const updateTableVisibility = () => {
                const isMobile = window.innerWidth <= 640;
                const isSmall = window.innerWidth <= 767;
                const isMedium = window.innerWidth <= 1023;

                const columns = [{
                        index: 2,
                        class: 'sm:table-cell'
                    }, // Wages
                    {
                        index: 3,
                        class: 'md:table-cell'
                    }, // OT Rate
                    {
                        index: 7,
                        class: 'sm:table-cell'
                    }, // Overtime Earnings
                    {
                        index: 8,
                        class: 'md:table-cell'
                    }, // Other Earnings
                    {
                        index: 10,
                        class: 'sm:table-cell'
                    }, // Misc Deduction
                    {
                        index: 11,
                        class: 'md:table-cell'
                    }, // Bank Adv
                    {
                        index: 12,
                        class: 'lg:table-cell'
                    } // Total Deduction
                ];

                columns.forEach(col => {
                    const cells = table.querySelectorAll(
                        `th:nth-child(${col.index + 1}), td:nth-child(${col.index + 1})`);
                    cells.forEach(cell => {
                        cell.style.display = isMobile ? 'none' : (isSmall && col.class.includes(
                            'md') ? 'none' : (isMedium && col.class.includes('lg') ?
                            'none' : ''));
                    });
                });
            };

            // Initial setup
            updateTableVisibility();

            // Update on resize with debounce
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(updateTableVisibility, 100);
            });
        });
    </script>

    @include('partials.js')
</body>

</html>
