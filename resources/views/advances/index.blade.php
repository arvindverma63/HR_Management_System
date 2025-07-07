<!DOCTYPE html>
<html lang="en">
@include('partials.head')

<body class="bg-gradient-to-br from-gray-50 to-gray-200 font-sans antialiased">
    <div class="flex min-h-screen">
        @include('partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 p-3 md:p-6 w-full">
            <!-- Mobile Menu Button -->
            <button id="open-sidebar"
                class="md:hidden fixed top-3 left-3 z-30 bg-custom-blue text-white p-1.5 rounded-md focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            @include('partials.header')

            <!-- Content -->
            <div class="bg-white rounded-md shadow p-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Advances</h2>
                    <a href="{{ route('advances.create') }}"
                        class="bg-custom-blue text-white px-3 py-1.5 rounded-md hover:bg-blue-700 text-sm">
                        New Advance
                    </a>
                </div>

                <!-- Alerts -->
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded mb-3 text-sm">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded mb-3 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Filter and Search Form -->
                <div class="mb-3 flex flex-col md:flex-row md:items-end gap-3">
                    <div class="w-full md:w-auto">
                        <label for="location_filter" class="block text-xs font-medium text-gray-700">Location</label>
                        <select id="location_filter" name="location_id"
                            class="mt-1 w-full md:w-48 p-1.5 border rounded-md focus:ring-1 focus:ring-custom-blue text-sm">

                            @foreach ($locations as $location)
                                @if (Auth::user()->role == 'admin')
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endif
                                @if (Auth::user()->role == 'hr')
                                    @if ($location->id == Session::get('location_id'))
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endif
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full md:w-auto">
                        <label for="employee_search" class="block text-xs font-medium text-gray-700">Employee ID</label>
                        <div class="flex">
                            <input type="text" id="employee_search" name="employee_search"
                                value="{{ request('employee_search') }}" placeholder="Search Employee ID..."
                                class="mt-1 w-full p-1.5 border rounded-md focus:ring-1 focus:ring-custom-blue text-sm">
                            <button type="button" onclick="applyFilters()"
                                class="ml-2 bg-custom-blue text-white py-1.5 px-3 rounded-md hover:bg-blue-700 text-sm">Search</button>
                        </div>
                    </div>
                </div>

                <!-- Advances Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 text-sm">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-2 px-3 text-left text-gray-600 font-medium">ID</th>
                                <th class="py-2 px-3 text-left text-gray-600 font-medium">Employee</th>
                                <th class="py-2 px-3 text-left text-gray-600 font-medium">Clims ID</th>
                                <th class="py-2 px-3 text-left text-gray-600 font-medium">Form ID</th>
                                <th class="py-2 px-3 text-left text-gray-600 font-medium">Amount</th>
                                <th class="py-2 px-3 text-left text-gray-600 font-medium">Bank</th>
                                <th class="py-2 px-3 text-left text-gray-600 font-medium">Notes</th>
                                <th class="py-2 px-3 text-left text-gray-600 font-medium">Status</th>
                                @if (Auth::user()->role === 'admin')
                                    <th class="py-2 px-3 text-left text-gray-600 font-medium">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($advances as $advance)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-1.5 px-3 border-t">{{ $advance->id }}</td>
                                    <td class="py-1.5 px-3 border-t">
                                        {{ $advance->employee ? $advance->employee->name : 'N/A' }}</td>
                                    <td class="py-1.5 px-3 border-t">
                                        {{ $advance->employee ? $advance->employee->clims_id : 'N/A' }}</td>
                                    <td class="py-1.5 px-3 border-t">
                                        {{ $advance->employee ? $advance->employee->employee_unique_id : 'N/A' }}</td>
                                    <td class="py-1.5 px-3 border-t">{{ number_format($advance->money, 2) }}</td>
                                    <td class="py-1.5 px-3 border-t">
                                        {{ $advance->employee ? $advance->employee->bank_name : 'N/A' }}</td>
                                    <td class="py-1.5 px-3 border-t">{{ $advance->notes ?? 'N/A' }}</td>
                                    <td class="py-1.5 px-3 border-t">
                                        <span
                                            class="inline-block px-1.5 py-0.5 rounded text-xs {{ $advance->status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $advance->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    @if (Auth::user()->role === 'admin')
                                        <td class="py-1.5 px-3 border-t flex items-center gap-1.5">
                                            <a href="{{ route('advances.show', $advance->id) }}"
                                                class="bg-blue-500 text-white p-1.5 rounded-md hover:bg-blue-600 transition duration-200 relative group"
                                                title="View Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('advances.edit', $advance->id) }}"
                                                class="bg-yellow-500 text-white p-1.5 rounded-md hover:bg-yellow-600 transition duration-200 relative group"
                                                title="Edit Advance">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-ascircleci2.828 15H6a2 2 0 00-2 2v11z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('advances.updateStatus', $advance->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status"
                                                    value="{{ $advance->status == 1 ? 0 : 1 }}">
                                                <button type="submit"
                                                    class="bg-purple-500 text-white p-1.5 rounded-md hover:bg-purple-600 transition duration-200 relative group"
                                                    title="Toggle Status">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 3c-2.355 0-4.573.606-6.518 1.984m0 0l2.518 2.518M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('advances.destroy', $advance->id) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this advance?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-500 text-white p-1.5 rounded-md hover:bg-red-600 transition duration-200 relative group"
                                                    title="Delete Advance">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V5a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->role === 'admin' ? 9 : 8 }}"
                                        class="py-2 px-3 border-t text-center text-gray-600">No advances found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $advances->appends(request()->query())->links('pagination::tailwind') }}
                </div>
            </div>
        </div>

        @include('partials.js')

        <script>
            function applyFilters() {
                const locationId = document.getElementById('location_filter').value;
                const employeeSearch = document.getElementById('employee_search').value;
                let url = '/advances?';
                if (locationId) url += `location_id=${locationId}&`;
                if (employeeSearch) url += `employee_search=${encodeURIComponent(employeeSearch)}`;
                window.location.href = url;
            }

            document.getElementById('location_filter').addEventListener('change', applyFilters);
            document.getElementById('employee_search').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    applyFilters();
                }
            });
        </script>
    </div>
</body>

</html>
