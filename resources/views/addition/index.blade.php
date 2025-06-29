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

            <!-- Location Selector and Add Button -->
            <div class="mt-8 flex flex-col md:flex-row justify-between items-center">
                <div class="w-full md:w-1/3 mb-4 md:mb-0">
                    <label for="location" class="block text-sm font-medium text-gray-700">Select Location</label>
                    <select id="location" name="location_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-custom-blue focus:ring focus:ring-custom-blue focus:ring-opacity-50">
                        <option value="">-- Select Location --</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
                <a href="{{ route('additions.create') }}" class="bg-custom-blue text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    Add New Addition
                </a>
            </div>

            <!-- Employee Additions Table -->
            <div class="mt-6 bg-white shadow-md rounded-lg overflow-hidden">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-custom-blue text-white">
                            <th class="p-4 text-left">Employee</th>
                            <th class="p-4 text-left">Type</th>
                            <th class="p-4 text-left">Rate</th>
                            <th class="p-4 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="additions-table">
                        @foreach ($additions as $addition)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-4">{{ $addition->employee->name ?? 'N/A' }}</td>
                                <td class="p-4">{{ $addition->type }}</td>
                                <td class="p-4">{{ number_format($addition->rate, 2) }}</td>
                                <td class="p-4 flex space-x-2">
                                    <a href="{{ route('additions.edit', $addition->id) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                    <form action="{{ route('additions.destroy', $addition->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this addition?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('partials.js')

    <!-- JavaScript for Location Filter -->
    <script>
        document.getElementById('location').addEventListener('change', function() {
            const locationId = this.value;
            fetch(`/additions?location_id=${locationId}`)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('additions-table');
                    tableBody.innerHTML = '';
                    data.additions.forEach(addition => {
                        tableBody.innerHTML += `
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-4">${addition.employee_name || 'N/A'}</td>
                                <td class="p-4">${addition.type}</td>
                                <td class="p-4">${Number(addition.rate).toFixed(2)}</td>
                                <td class="p-4 flex space-x-2">
                                    <a href="/additions/${addition.id}/edit" class="text-blue-600 hover:text-blue-800">Edit</a>
                                    <form action="/additions/${addition.id}" method="POST" class="inline">
                                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this addition?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        `;
                    });
                })
                .catch(error => console.error('Error fetching additions:', error));
        });
    </script>
</body>
</html>
