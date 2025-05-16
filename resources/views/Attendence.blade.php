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
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 md:mb-6 space-y-4 md:space-y-0">
                        <div class="w-full md:w-auto">
                            <label for="attendance-date" class="block text-sm font-medium text-gray-700">Select
                                Date</label>
                            <input type="date" id="attendance-date"
                                class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                value="2025-05-16">
                        </div>
                        <div class="w-full md:w-auto">
                            <input type="text" id="search"
                                class="w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                placeholder="Search employees...">
                        </div>
                    </div>
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
                                <tr class="border-b hover:bg-gray-50 transition-all">
                                    <td class="p-2 md:p-4">John Doe</td>
                                    <td class="p-2 md:p-4">IT</td>
                                    <td class="p-2 md:p-4">
                                        <select
                                            class="p-1 md:p-2 border rounded-lg focus:ring-2 focus:ring-custom-blue transition-all w-full">
                                            <option value="present">Present</option>
                                            <option value="absent">Absent</option>
                                            <option value="leave">Leave</option>
                                        </select>
                                    </td>
                                    <td class="p-2 md:p-4">
                                        <input type="text"
                                            class="p-1 md:p-2 border rounded-lg w-full focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                            placeholder="Add notes...">
                                    </td>
                                </tr>
                                <tr class="border-b hover:bg-gray-50 transition-all">
                                    <td class="p-2 md:p-4">Jane Smith</td>
                                    <td class="p-2 md:p-4">HR</td>
                                    <td class="p-2 md:p-4">
                                        <select
                                            class="p-1 md:p-2 border rounded-lg focus:ring-2 focus:ring-custom-blue transition-all w-full">
                                            <option value="present">Present</option>
                                            <option value="absent">Absent</option>
                                            <option value="leave">Leave</option>
                                        </select>
                                    </td>
                                    <td class="p-2 md:p-4">
                                        <input type="text"
                                            class="p-1 md:p-2 border rounded-lg w-full focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                            placeholder="Add notes...">
                                    </td>
                                </tr>
                                <tr class="border-b hover:bg-gray-50 transition-all">
                                    <td class="p-2 md:p-4">Mike Johnson</td>
                                    <td class="p-2 md:p-4">Finance</td>
                                    <td class="p-2 md:p-4">
                                        <select
                                            class="p-1 md:p-2 border rounded-lg focus:ring-2 focus:ring-custom-blue transition-all w-full">
                                            <option value="present">Present</option>
                                            <option value="absent">Absent</option>
                                            <option value="leave">Leave</option>
                                        </select>
                                    </td>
                                    <td class="p-2 md:p-4">
                                        <input type="text"
                                            class="p-1 md:p-2 border rounded-lg w-full focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                            placeholder="Add notes...">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center mt-4 md:mt-6 space-y-4 md:space-y-0">
                        <div>
                            <span class="text-gray-600 text-sm md:text-base">Showing 1 to 3 of 3 entries</span>
                        </div>
                        <div class="flex space-x-2">
                            <button
                                class="px-3 md:px-4 py-1 md:py-2 bg-custom-blue text-white rounded-lg hover:bg-custom-blue-dark transition-all disabled:bg-gray-400 text-sm md:text-base"
                                disabled>Previous</button>
                            <button
                                class="px-3 md:px-4 py-1 md:py-2 bg-custom-blue text-white rounded-lg hover:bg-custom-blue-dark transition-all text-sm md:text-base">1</button>
                            <button
                                class="px-3 md:px-4 py-1 md:py-2 bg-custom-blue text-white rounded-lg hover:bg-custom-blue-dark transition-all disabled:bg-gray-400 text-sm md:text-base"
                                disabled>Next</button>
                        </div>
                    </div>
                    <button
                        class="mt-4 md:mt-6 w-full bg-custom-blue text-white py-2 md:py-3 rounded-lg hover:bg-custom-blue-dark transition-all">Submit
                        Attendance</button>
                </div>
            </section>
        </div>
    </div>


</body>
@include('partials.js')
</html>
