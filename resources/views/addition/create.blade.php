<!DOCTYPE html>
<html lang="en">
@include('partials.head')

<body class="bg-gradient-to-br from-gray-50 to-gray-200 font-sans antialiased">
    <div class="flex min-h-screen">
        @include('partials.sidebar')

        <div class="flex-1 p-4 md:p-8 w-full">
            <button id="open-sidebar"
                class="md:hidden fixed top-4 left-4 z-30 bg-custom-blue text-white p-2 rounded-lg focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            @include('partials.header')

            <div class="mt-8 max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New Addition</h2>
                <form action="{{ route('additions.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <select id="location" name="location_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-custom-blue focus:ring focus:ring-custom-blue focus:ring-opacity-50">
                            <option value="">-- Select Location --</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="employee" class="block text-sm font-medium text-gray-700">Employee</label>
                        <select id="employee" name="employee_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-custom-blue focus:ring focus:ring-custom-blue focus:ring-opacity-50">
                            <option value="">-- Select Employee --</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" data-location="{{ $employee->location_id }}">
                                    {{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                        <select id="addDeductionType" name="type"
                            class="w-full border-gray-300 rounded-lg shadow-sm p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            required>
                            <option value="">Select Type</option>
                            <option value="CASH">CASH</option>
                            <option value="MISC">MISC</option>
                            <option value="OTHER">OTHER</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="rate" class="block text-sm font-medium text-gray-700">Rate</label>
                        <input type="number" id="rate" name="rate" step="0.01"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-custom-blue focus:ring focus:ring-custom-blue focus:ring-opacity-50"
                            required>
                    </div>
                    <button type="submit"
                        class="bg-custom-blue text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">Save</button>
                </form>
            </div>
        </div>
    </div>

    @include('partials.js')

    <script>
        document.getElementById('location').addEventListener('change', function() {
            const locationId = this.value;
            const employeeSelect = document.getElementById('employee');
            const options = employeeSelect.querySelectorAll('option[data-location]');
            options.forEach(option => {
                option.style.display = (locationId === '' || option.getAttribute('data-location') ===
                    locationId) ? 'block' : 'none';
            });
        });
    </script>
</body>

</html>
