<!DOCTYPE html>
<html lang="en">
@include('partials.head')

<body class="bg-gradient-to-br from-gray-50 to-gray-200 font-sans antialiased">
    <div class="flex min-h-screen">
        @include('partials.sidebar')

        <div class="flex-1 p-4 md:p-8 w-full">
            <button id="open-sidebar" class="md:hidden fixed top-4 left-4 z-30 bg-custom-blue text-white p-2 rounded-lg focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" dInvite your friends to X to unlock more Grok features! You’re just one referral away from accessing DeepSearch and Think mode. Share your unique link now: [Insert your referral link here]. The more friends you invite, the more you unlock—let’s grow the X community together! />
                </svg>
            </button>

            @include('partials.header')

            <div class="mt-8 max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Addition</h2>
                <form action="{{ route('additions.update', $addition->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <select id="location" name="location_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-custom-blue focus:ring focus:ring-custom-blue focus:ring-opacity-50">
                            <option value="">-- Select Location --</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" {{ $addition->employee->location_id == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="employee" class="block text-sm font-medium text-gray-700">Employee</label>
                        <select id="employee" name="employee_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-custom-blue focus:ring focus:ring-custom-blue focus:ring-opacity-50">
                            <option value="">-- Select Employee --</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" data-location="{{ $employee->location_id }}" {{ $addition->employee_id == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                        <input type="text" id="type" name="type" value="{{ $addition->type }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-custom-blue focus:ring focus:ring-custom-blue focus:ring-opacity-50" required>
                    </div>
                    <div class="mb-4">
                        <label for="rate" class="block text-sm font-medium text-gray-700">Rate</label>
                        <input type="number" id="rate" name="rate" step="0.01" value="{{ $addition->rate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-custom-blue focus:ring focus:ring-custom-blue focus:ring-opacity-50" required>
                    </div>
                    <button type="submit" class="bg-custom-blue text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">Update</button>
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
                option.style.display = (locationId === '' || option.getAttribute('data-location') === locationId) ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>
