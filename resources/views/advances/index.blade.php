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

            <!-- Content -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Advances</h2>
                    <a href="{{ route('advances.create') }}"
                        class="bg-custom-blue text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Create New Advance
                    </a>
                </div>

                <!-- Alerts -->
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Filter and Search Form -->
                <div class="mb-4 flex flex-col md:flex-row md:items-end gap-4">
                    <div>
                        <label for="location_filter" class="block text-sm font-medium text-gray-700">Filter by
                            Location</label>
                        <select id="location_filter" name="location_id"
                            class="mt-1 w-full md:w-1/4 p-2 border rounded-lg focus:ring-2 focus:ring-custom-blue">
                            <option value="">All Locations</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}"
                                    {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="employee_search" class="block text-sm font-medium text-gray-700">Search by Employee
                            ID</label>
                        <div class="flex">
                            <input type="text" id="employee_search" name="employee_search"
                                value="{{ request('employee_search') }}" placeholder="Search by Employee ID..."
                                class="mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-custom-blue">
                            <button type="button" onclick="applyFilters()"
                                class="ml-2 bg-custom-blue text-white py-2 px-4 rounded-lg hover:bg-blue-700">Search</button>
                        </div>
                    </div>
                </div>

                <!-- Advances Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-3 px-4 text-left text-gray-600 font-medium">ID</th>
                                <th class="py-3 px-4 text-left text-gray-600 font-medium">Employee</th>
                                <th class="py-3 px-4 text-left text-gray-600 font-medium">Employee ID</th>
                                <th class="py-3 px-4 text-left text-gray-600 font-medium">Amount</th>
                                <th class="py-3 px-4 text-left text-gray-600 font-medium">Notes</th>
                                <th class="py-3 px-4 text-left text-gray-600 font-medium">Status</th>
                                @if (Auth::user()->role === 'admin')
                                    <th class="py-3 px-4 text-left text-gray-600 font-medium">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($advances as $advance)
                                <tr>
                                    <td class="py-2 px-4 border-t">{{ $advance->id }}</td>
                                    <td class="py-2 px-4 border-t">
                                        {{ $advance->employee ? $advance->employee->name : 'N/A' }}</td>
                                    <td class="py-2 px-4 border-t">
                                        {{ $advance->employee ? $advance->employee->employee_unique_id : 'N/A' }}</td>
                                    <td class="py-2 px-4 border-t">{{ number_format($advance->money, 2) }}</td>
                                    <td class="py-2 px-4 border-t">{{ $advance->notes ?? 'N/A' }}</td>
                                    <td class="py-2 px-4 border-t">
                                        <span
                                            class="inline-block px-2 py-1 rounded text-sm {{ $advance->status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $advance->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    @if (Auth::user()->role === 'admin')
                                        <td class="py-2 px-4 border-t flex space-x-2">
                                            <a href="{{ route('advances.show', $advance->id) }}"
                                                class="text-blue-600 hover:underline">View</a>
                                            <a href="{{ route('advances.edit', $advance->id) }}"
                                                class="text-yellow-600 hover:underline">Edit</a>
                                            <form action="{{ route('advances.updateStatus', $advance->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status"
                                                    value="{{ $advance->status == 1 ? 0 : 1 }}">
                                                <button type="submit" class="text-purple-600 hover:underline">Toggle
                                                    Status</button>
                                            </form>
                                            <form action="{{ route('advances.destroy', $advance->id) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this advance?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:underline">Delete</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->role === 'admin' ? 7 : 6 }}"
                                        class="py-2 px-4 border-t text-center text-gray-600">No advances found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $advances->appends(request()->query())->links('pagination::tailwind') }}
                </div>
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

        // Trigger filter on location change or enter key in search
        document.getElementById('location_filter').addEventListener('change', applyFilters);
        document.getElementById('employee_search').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
    </script>
</body>

</html>
