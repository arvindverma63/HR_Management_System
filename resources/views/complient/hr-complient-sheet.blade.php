<!DOCTYPE html>
<html lang="en">

@include('partials.head')

<body class="bg-gradient-to-br from-gray-100 to-gray-300 font-sans antialiased min-h-screen overflow-hidden">
    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <!-- Mobile Menu Button -->
            <button id="open-sidebar"
                class="md:hidden fixed top-4 left-4 z-30 bg-custom-blue text-white p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-custom-blue transition"
                aria-label="Open sidebar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 w-full">
                @include('partials.header')

                <!-- HR Report Content -->
                <div class="bg-white rounded-2xl shadow-xl p-4 sm:p-5 md:p-6 lg:p-8 mt-6">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6">HR ComplientSheet Report</h2>

                    <!-- Form for Month and Year -->
                    <form method="POST" action="{{ route('workman.complient.report') }}"
                        class="flex flex-col sm:flex-row gap-3 sm:gap-4 mb-4 sm:mb-6">
                        @csrf
                        <div class="flex-1">
                            <label for="month" class="block text-sm font-medium text-gray-700">Month</label>
                            <select id="month" name="month"
                                class="mt-1 block w-full rounded-md border-gray-200 text-sm shadow-sm focus:border-custom-blue focus:ring focus:ring-custom-blue focus:ring-opacity-50 transition duration-150">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}"
                                        {{ old('month', date('n')) == $i ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>
                            @error('month')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex-1">
                            <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                            <input type="number" id="year" name="year" value="{{ old('year', date('Y')) }}"
                                min="1900" max="9999"
                                class="mt-1 block w-full rounded-md border-gray-200 text-sm shadow-sm focus:border-custom-blue focus:ring focus:ring-custom-blue focus:ring-opacity-50 transition duration-150">
                            @error('year')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex items-end">
                            <button type="submit"
                                class="bg-custom-blue text-white px-4 py-2 rounded-md text-sm hover:bg-opacity-90 focus:ring-2 focus:ring-custom-blue focus:ring-opacity-50 transition duration-150">
                                Generate Report
                            </button>
                        </div>
                    </form>

                    <!-- Report Table -->
                    @if (isset($report))
                        <div class="overflow-x-auto relative rounded-lg border border-gray-100 scroll-smooth">
                            <table id="hrTable" class="w-[1600px] text-xs text-left text-gray-700" id="myTable">
                                <thead
                                    class="text-xs font-semibold uppercase bg-gradient-to-r from-custom-blue to-custom-blue/90 text-white">
                                    <tr>
                                        <th scope="col" data-sort="name" data-order="asc"
                                            class="px-2 py-2 md:px-3 md:py-3 cursor-pointer hover:bg-custom-blue/80 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <span>Name</span>
                                                <span
                                                    class="sort-icon opacity-50 hover:opacity-100 transition-opacity"></span>
                                            </div>
                                        </th>
                                        <th scope="col" data-sort="rate_per_month" data-order="asc"
                                            class="px-2 py-2 md:px-3 md:py-3 cursor-pointer hover:bg-custom-blue/80 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <span>Rate/Month</span>
                                                <span
                                                    class="sort-icon opacity-50 hover:opacity-100 transition-opacity"></span>
                                            </div>
                                        </th>
                                        <th scope="col" data-sort="rate_of_wages" data-order="asc"
                                            class="hidden sm:table-cell px-2 py-2 md:px-3 md:py-3 cursor-pointer hover:bg-custom-blue/80 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <span>Wages</span>
                                                <span
                                                    class="sort-icon opacity-50 hover:opacity-100 transition-opacity"></span>
                                            </div>
                                        </th>
                                        <th scope="col" data-sort="rate_of_ot" data-order="asc"
                                            class="hidden md:table-cell px-2 py-2 md:px-3 md:py-3 cursor-pointer hover:bg-custom-blue/80 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <span>OT Rate</span>
                                                <span
                                                    class="sort-icon opacity-50 hover:opacity-100 transition-opacity"></span>
                                            </div>
                                        </th>
                                        <th scope="col" data-sort="days_worked" data-order="asc"
                                            class="px-2 py-2 md:px-3 md:py-3 cursor-pointer hover:bg-custom-blue/80 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <span>Days</span>
                                                <span
                                                    class="sort-icon opacity-50 hover:opacity-100 transition-opacity"></span>
                                            </div>
                                        </th>
                                        <th scope="col" data-sort="overtime_hours" data-order="asc"
                                            class="hidden md:table-cell px-2 py-2 md:px-3 md:py-3 cursor-pointer hover:bg-custom-blue/80 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <span>OT Hours</span>
                                                <span
                                                    class="sort-icon opacity-50 hover:opacity-100 transition-opacity"></span>
                                            </div>
                                        </th>
                                        <th scope="col" data-sort="basic_earnings" data-order="asc"
                                            class="px-2 py-2 md:px-3 md:py-3 cursor-pointer hover:bg-custom-blue/80 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <span>Earnings: Basic</span>
                                                <span
                                                    class="sort-icon opacity-50 hover:opacity-100 transition-opacity"></span>
                                            </div>
                                        </th>
                                        <th scope="col" data-sort="overtime_earnings" data-order="asc"
                                            class="hidden md:table-cell px-2 py-2 md:px-3 md:py-3 cursor-pointer hover:bg-custom-blue/80 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <span>Earnings: Overtime</span>
                                                <span
                                                    class="sort-icon opacity-50 hover:opacity-100 transition-opacity"></span>
                                            </div>
                                        </th>
                                        <th scope="col" data-sort="other_earnings" data-order="asc"
                                            class="hidden lg:table-cell px-2 py-2 md:px-3 md:py-3 cursor-pointer hover:bg-custom-blue/80 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <span>Earnings: Others</span>
                                                <span
                                                    class="sort-icon opacity-50 hover:opacity-100 transition-opacity"></span>
                                            </div>
                                        </th>
                                        <th scope="col" data-sort="cash_deduction" data-order="asc"
                                            class="px-2 py-2 md:px-3 md:py-3 cursor-pointer hover:bg-custom-blue/80 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <span>Deductions: Cash</span>
                                                <span
                                                    class="sort-icon opacity-50 hover:opacity-100 transition-opacity"></span>
                                            </div>
                                        </th>
                                        <th scope="col" data-sort="misc_recovery" data-order="asc"
                                            class="hidden md:table-cell px-2 py-2 md:px-3 md:py-3 cursor-pointer hover:bg-custom-blue/80 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <span>Deductions: Misc</span>
                                                <span
                                                    class="sort-icon opacity-50 hover:opacity-100 transition-opacity"></span>
                                            </div>
                                        </th>
                                        <th scope="col" data-sort="bank_adv" data-order="asc"
                                            class="hidden lg:table-cell px-2 py-2 md:px-3 md:py-3 cursor-pointer hover:bg-custom-blue/80 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <span>Deductions: Bank Adv</span>
                                                <span
                                                    class="sort-icon opacity-50 hover:opacity-100 transition-opacity"></span>
                                            </div>
                                        </th>
                                        <th scope="col" data-sort="total_deduction" data-order="asc"
                                            class="hidden xl:table-cell px-2 py-2 md:px-3 md:py-3 cursor-pointer hover:bg-custom-blue/80 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <span>Deductions: Total</span>
                                                <span
                                                    class="sort-icon opacity-50 hover:opacity-100 transition-opacity"></span>
                                            </div>
                                        </th>
                                        <th scope="col" data-sort="net_payments" data-order="asc"
                                            class="px-2 py-2 md:px-3 md:py-3 cursor-pointer hover:bg-custom-blue/80 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <span>Net Pay</span>
                                                <span
                                                    class="sort-icon opacity-50 hover:opacity-100 transition-opacity"></span>
                                            </div>
                                        </th>
                                        <th scope="col" data-sort="net_payments" data-order="asc"
                                            class="px-2 py-2 md:px-3 md:py-3 cursor-pointer hover:bg-custom-blue/80 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <span>Slip</span>
                                                <span
                                                    class="sort-icon opacity-50 hover:opacity-100 transition-opacity"></span>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach ($report as $row)
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="px-2 py-3 md:px-3 md:py-4 text-gray-800" data-label="Name">
                                                {{ $row->name }}
                                            </td>
                                            <td class="px-2 py-3 md:px-3 md:py-4 text-gray-800"
                                                data-label="Rate/Month">
                                                {{ number_format($row->rate_per_month, 2) }}
                                            </td>
                                            <td class="hidden sm:table-cell px-2 py-3 md:px-3 md:py-4 text-gray-800"
                                                data-label="Wages">
                                                {{ number_format($row->rate_of_wages, 2) }}
                                            </td>
                                            <td class="hidden md:table-cell px-2 py-3 md:px-3 md:py-4 text-gray-800"
                                                data-label="OT Rate">
                                                {{ number_format($row->rate_of_ot, 2) }}
                                            </td>
                                            <td class="px-2 py-3 md:px-3 md:py-4 text-gray-800" data-label="Days">
                                                {{ $row->days_worked }}
                                            </td>
                                            <td class="hidden md:table-cell px-2 py-3 md:px-3 md:py-4 text-gray-800"
                                                data-label="OT Hours">
                                                {{ number_format($row->overtime_hours, 2) }}
                                            </td>
                                            <td class="px-2 py-3 md:px-3 md:py-4 text-gray-800"
                                                data-label="Earnings: Basic">
                                                {{ number_format($row->basic_earnings, 2) }}
                                            </td>
                                            <td class="hidden md:table-cell px-2 py-3 md:px-3 md:py-4 text-gray-800"
                                                data-label="Earnings: Overtime">
                                                {{ number_format($row->overtime_earnings, 2) }}
                                            </td>
                                            <td class="hidden lg:table-cell px-2 py-3 md:px-3 md:py-4 text-gray-800"
                                                data-label="Earnings: Others">
                                                {{ number_format($row->other_earnings, 2) }}
                                            </td>
                                            <td class="px-2 py-3 md:px-3 md:py-4 text-gray-800"
                                                data-label="Deductions: Cash">
                                                {{ number_format($row->cash_deduction, 2) }}
                                            </td>
                                            <td class="hidden md:table-cell px-2 py-3 md:px-3 md:py-4 text-gray-800"
                                                data-label="Deductions: Misc">
                                                {{ number_format($row->misc_recovery, 2) }}
                                            </td>
                                            <td class="hidden lg:table-cell px-2 py-3 md:px-3 md:py-4 text-gray-800"
                                                data-label="Deductions: Bank Adv">
                                                {{ number_format($row->bank_adv, 2) }}
                                            </td>
                                            <td class="hidden xl:table-cell px-2 py-3 md:px-3 md:py-4 text-gray-800"
                                                data-label="Deductions: Total">
                                                {{ number_format($row->total_deduction, 2) }}
                                            </td>
                                            <td class="px-2 py-3 md:px-3 md:py-4 text-gray-800" data-label="Net Pay">
                                                {{ number_format($row->net_payments, 2) }}
                                            </td>
                                            <td class="px-2 py-3 md:px-3 md:py-4 text-gray-800" data-label="Net Pay">
                                                <form method="POST" action="{{ route('internal.payslip') }}"
                                                    >
                                                    @csrf
                                                    <div class="flex-1" style="display: none;">
                                                        <label for="month"
                                                            class="block text-sm font-medium text-gray-700">Month</label>
                                                        <select id="month" name="month"
                                                            class="mt-1 block w-full rounded-md border-gray-200 text-sm shadow-sm focus:border-custom-blue focus:ring focus:ring-custom-blue focus:ring-opacity-50 transition duration-150">
                                                            @for ($i = 1; $i <= 12; $i++)
                                                                <option value="{{ $i }}"
                                                                    {{ old('month', date('n')) == $i ? 'selected' : '' }}>
                                                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                        @error('month')
                                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="flex-1" style="display: none;">
                                                        <label for="year"
                                                            class="block text-sm font-medium text-gray-700">Year</label>
                                                        <input type="number" id="year" name="year"
                                                            value="{{ old('year', date('Y')) }}" min="1900"
                                                            max="9999"
                                                            class="mt-1 block w-full rounded-md border-gray-200 text-sm shadow-sm focus:border-custom-blue focus:ring focus:ring-custom-blue focus:ring-opacity-50 transition duration-150">
                                                        @error('year')
                                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <input type="number" name="id" value="{{ $row->id }}" style="display: none;">

                                                        <button type="submit"
                                                            <i class="fa-solid fa-download"></i>
                                                        </button>

                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @elseif (isset($report))
                        <p class="text-gray-600 text-base">No data available for the selected month and year.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS for Sort Icons, Scrollbar, and Responsive Adjustments -->
    <style>
        /* Sort Icons */
        .sort-icon::after {
            content: '↕';
            font-size: 0.75rem;
        }

        th[data-order="asc"] .sort-icon::after {
            content: '↑';
        }

        th[data-order="desc"] .sort-icon::after {
            content: '↓';
        }

        /* Enhanced Scrollbar for Small Laptops (Horizontal Scroll for Table) */
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: auto;
            scrollbar-color: #134a6b #e5e7eb;
        }

        .overflow-x-auto::-webkit-scrollbar {
            height: 10px;
            /* Thicker scrollbar for better visibility */
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #e5e7eb;
            border-radius: 4px;
            box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.1);
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #134a6b;
            border-radius: 4px;
            border: 2px solid #e5e7eb;
            /* Adds padding effect */
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #0f3a54;
            /* Darker shade of #134a6b on hover */
        }

        /* Vertical Scrollbar for Main Content */
        .overflow-y-auto::-webkit-scrollbar {
            width: 8px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: #e5e7eb;
            border-radius: 4px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #134a6b;
            border-radius: 4px;
            border: 2px solid #e5e7eb;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #0f3a54;
        }

        /* Ensure scroll on small laptops (1024px to 1280px) */
        @media (min-width: 1024px) and (max-width: 1280px) {
            #hrTable {
                width: 1600px;
                /* Force table to be wider than viewport to trigger scroll */
            }
        }

        /* Mobile Responsive Styles for Table */
        @media (max-width: 640px) {
            #hrTable {
                display: block;
                width: 100%;
                /* Reset width for mobile */
            }

            #hrTable thead {
                display: none;
            }

            #hrTable tbody,
            #hrTable tr {
                display: block;
            }

            #hrTable tr {
                margin-bottom: 1rem;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                padding: 1rem;
                background: white;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            #hrTable td {
                display: flex;
                justify-content: space-between;
                padding: 0.5rem 0;
                border: none;
            }

            #hrTable td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #374151;
                flex: 1;
            }
        }

        /* Ensure sidebar is hidden by default on mobile */
        @media (max-width: 767px) {
            #sidebar {
                display: none;
            }

            #sidebar.open {
                display: flex;
            }
        }
    </style>

    <!-- Custom JavaScript for Sorting and Sidebar Toggle -->
    {{-- <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', () => {
            // Table Sorting
            const table = document.getElementById('hrTable');
            const headers = table.querySelectorAll('th[data-sort]');

            headers.forEach(header => {
                header.addEventListener('click', () => {
                    const sortKey = header.dataset.sort;
                    const order = header.dataset.order === 'asc' ? 'desc' : 'asc';
                    header.dataset.order = order;

                    // Update sort icons
                    headers.forEach(h => h.dataset.order = h === header ? order : 'asc');

                    // Get rows and sort
                    const tbody = table.querySelector('tbody');
                    const rows = Array.from(tbody.querySelectorAll('tr'));

                    rows.sort((a, b) => {
                        let aValue = a.querySelector(
                                `td[data-label="${header.textContent.trim()}"]`).textContent
                            .trim();
                        let bValue = b.querySelector(
                                `td[data-label="${header.textContent.trim()}"]`).textContent
                            .trim();

                        // Handle numeric values
                        aValue = aValue.replace(/,/g, '');
                        bValue = bValue.replace(/,/g, '');
                        aValue = isNaN(aValue) ? aValue : parseFloat(aValue);
                        bValue = isNaN(bValue) ? bValue : parseFloat(bValue);

                        if (order === 'asc') {
                            return aValue > bValue ? 1 : -1;
                        } else {
                            return aValue < bValue ? 1 : -1;
                        }
                    });

                    // Re-append sorted rows
                    tbody.innerHTML = '';
                    rows.forEach(row => tbody.appendChild(row));
                });
            });

            // Sidebar Toggle for Mobile
            const sidebar = document.getElementById('sidebar');
            const openSidebarBtn = document.getElementById('open-sidebar');
            const closeSidebarBtn = document.getElementById('close-sidebar');

            openSidebarBtn.addEventListener('click', () => {
                sidebar.classList.add('open');
            });

            closeSidebarBtn.addEventListener('click', () => {
                sidebar.classList.remove('open');
            });
        });
    </script> --}}

    @include('partials.js')
</body>

</html>
