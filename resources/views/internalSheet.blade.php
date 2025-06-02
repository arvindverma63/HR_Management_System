<!DOCTYPE html>
<html lang="en">
@include('partials.head')

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
                @if (isset($report) && $report->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table id="myTable"
                            class="min-w-full table-auto text-sm text-left text-gray-700 border border-gray-200">
                            <thead class="bg-gray-100 text-xs uppercase font-semibold text-gray-600">

                                <tr>
                                    <th
                                        class="sticky left-0 bg-gray-50 px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th
                                        class="sticky left-0 bg-gray-50 px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
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
                                        class="sticky left-[80px] sm:left-[100px] bg-gray-50 px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Days
                                    </th>
                                    <th
                                        class="px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        OT Hours
                                    </th>
                                    <th
                                        class="sticky left-[140px] sm:left-[160px] bg-gray-50 px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Earnings
                                    </th>
                                    <th
                                        class="hidden sm:table-cell px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                                    </th>
                                    <th
                                        class="hidden md:table-cell px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                                    </th>
                                    <th
                                        class="sticky left-[200px] sm:left-[220px] bg-gray-50 px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Deductions
                                    </th>
                                    <th
                                        class="hidden sm:table-cell px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                                    </th>
                                    <th
                                        class="hidden md:table-cell px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                                    </th>
                                    <th
                                        class="hidden lg:table-cell px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                                    </th>
                                    <th
                                        class="sticky right-0 bg-gray-50 px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Net Pay
                                    </th>
                                </tr>
                                <tr>
                                    <th class="sticky left-0 bg-gray-50 px-2 sm:px-4 py-2 sm:py-3"></th>
                                    <th class="hidden sm:table-cell px-2 sm:px-4 py-2 sm:py-3"></th>
                                    <th class="hidden md:table-cell px-2 sm:px-4 py-2 sm:py-3"></th>
                                    <th class="sticky left-[80px] sm:left-[100px] bg-gray-50 px-2 sm:px-4 py-2 sm:py-3">
                                    </th>
                                    <th class="px-2 sm:px-4 py-2 sm:py-3"></th>
                                    <th
                                        class="sticky left-[140px] sm:left-[160px] bg-gray-50 px-2 sm:px-4 py-2 sm:py-3">
                                        Basic</th>
                                    <th class="hidden sm:table-cell px-2 sm:px-4 py-2 sm:py-3">Overtime</th>
                                    <th class="hidden md:table-cell px-2 sm:px-4 py-2 sm:py-3">Others</th>
                                    <th
                                        class="sticky left-[200px] sm:left-[220px] bg-gray-50 px-2 sm:px-4 py-2 sm:py-3">
                                        Cash</th>
                                    <th class="hidden sm:table-cell px-2 sm:px-4 py-2 sm:py-3">Misc</th>
                                    <th class="hidden md:table-cell px-2 sm:px-4 py-2 sm:py-3">Bank Adv</th>
                                    <th class="hidden lg:table-cell px-2 sm:px-4 py-2 sm:py-3">Total</th>
                                    <th class="sticky right-0 bg-gray-50 px-2 sm:px-4 py-2 sm:py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($report as $row)
                                    <tr>
                                        <td class="sticky left-0 bg-white px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ $row->name }}</td>
                                        <td class="sticky left-0 bg-white px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ number_format($row->rate_per_month, 2) }}</td>
                                        <td class="hidden sm:table-cell px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ number_format($row->rate_of_wages, 2) }}</td>
                                        <td class="hidden md:table-cell px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ number_format($row->rate_of_ot, 2) }}</td>
                                        <td
                                            class="sticky left-[80px] sm:left-[100px] bg-white px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ $row->days_worked }}</td>
                                        <td class="px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ number_format($row->overtime_hours, 2) }}</td>
                                        <td
                                            class="sticky left-[140px] sm:left-[160px] bg-white px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ number_format($row->basic_earnings, 2) }}</td>
                                        <td class="hidden sm:table-cell px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ number_format($row->overtime_earnings, 2) }}</td>
                                        <td class="hidden md:table-cell px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ number_format($row->other_earnings, 2) }}</td>
                                        <td
                                            class="sticky left-[200px] sm:left-[220px] bg-white px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ number_format($row->cash_deduction, 2) }}</td>
                                        <td class="hidden sm:table-cell px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ number_format($row->misc_recovery, 2) }}</td>
                                        <td class="hidden md:table-cell px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ number_format($row->bank_adv, 2) }}</td>
                                        <td class="hidden lg:table-cell px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
                                            {{ number_format($row->total_deduction, 2) }}</td>
                                        <td class="sticky right-0 bg-white px-2 sm:px-4 py-2 sm:py-4 text-gray-900">
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

    @include('partials.js')
</body>

</html>
