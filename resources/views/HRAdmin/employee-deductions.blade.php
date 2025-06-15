<!DOCTYPE html>
<html lang="en">
@include('partials.head')

<body class="bg-gradient-to-br from-gray-50 to-gray-200 font-sans antialiased">
    <div class="flex min-h-screen">
        @include('partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 p-4 md:p-8 w-full">
            @include('partials.header')

            <!-- Session Flash Messages -->
            @if (session('success'))
                <div
                    class="max-w-3xl mx-auto mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border border-green-300">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-3xl mx-auto mb-4 px-4 py-3 rounded bg-red-100 text-red-800 border border-red-300">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="max-w-3xl mx-auto mb-4 px-4 py-3 rounded bg-red-100 text-red-800 border border-red-300">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 🔍 Search Section -->
            <div class="max-w-xl mx-auto my-8 bg-white shadow-md rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Find Location</h2>
                <form method="GET" action="{{ route('employee-deductions') }}"
                    class="flex flex-col md:flex-row md:items-end gap-4">
                    <div class="flex-1">
                        <label for="location_id" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <select id="location_id" name="location_id"
                            class="w-full border-gray-300 rounded-lg shadow-sm p-2 md:p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            required>
                            <option value="">Select Location</option>
                            @foreach ($locations as $loc)
                                <option value="{{ $loc->id }}"
                                    {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition w-full md:w-auto">
                        Search
                    </button>
                </form>
            </div>

            @if ($selectedLocation)
                <!-- Location Details -->
                <div class="max-w-xl mx-auto bg-white shadow rounded p-6 mb-8">
                    <h3 class="text-xl font-semibold mb-2">Location Details</h3>
                    <p><strong>Location ID:</strong> {{ $selectedLocation->id }}</p>
                    <p><strong>Name:</strong> {{ $selectedLocation->name ?? 'N/A' }}</p>
                </div>

                <!-- Employees List -->
                <div class="max-w-4xl mx-auto bg-white shadow rounded p-6">
                    <h3 class="text-xl font-semibold mb-4">Employees</h3>
                    @if ($employees->isEmpty())
                        <p class="text-gray-600">No employees found for this location.</p>
                    @else
                        <div class="overflow-x-auto w-full">
                            <table class="min-w-full border divide-y divide-gray-200" id="employeesTable">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Name</th>
                                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($employees as $employee)
                                        <tr>
                                            <td class="px-4 py-2">{{ $employee->name }}</td>
                                            <td class="px-4 py-2">
                                                <!-- Add Deduction Form -->
                                                <form method="POST" action="{{ route('employee-deductions.store') }}"
                                                    class="inline-block mb-2">
                                                    @csrf
                                                    <input type="hidden" name="employee_id"
                                                        value="{{ $employee->id }}">
                                                    <select name="type" class="border px-2 py-1 starkey-w-32">
                                                        <option value="">Select Type</option>
                                                        <option value="CASH">CASH</option>
                                                        <option value="MISC">MISC</option>
                                                        <option value="BANK ADV">BANK ADV</option>
                                                    </select>
                                                    <input type="number" name="rate" step="0.01"
                                                        placeholder="Rate" class="border px-2 py-1 starkey-w-20">
                                                    <button
                                                        class="bg-green-600 text-white px-2 py-1 rounded">Add</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <!-- Deductions Sub-table -->
                                        @if ($employee->deductions->isNotEmpty())
                                            <tr>
                                                <td colspan="2" class="px-4 py-2">
                                                    <div class="ml-8">
                                                        <h4 class="text-sm font-semibold mb-2">Deductions for
                                                            {{ $employee->name }}</h4>
                                                        <table class="min-w-full border divide-y divide-gray-200">
                                                            <thead class="bg-gray-50">
                                                                <tr>
                                                                    <th
                                                                        class="px-4 py-2 text-left text-xs font-medium text-gray-600">
                                                                        Type</th>
                                                                    <th
                                                                        class="px-4 py-2 text-left text-xs font-medium text-gray-600">
                                                                        Rate</th>
                                                                    <th
                                                                        class="px-4 py-2 text-left text-xs font-medium text-gray-600">
                                                                        Created At</th>
                                                                    <th
                                                                        class="px-4 py-2 text-left text-xs font-medium text-gray-600">
                                                                        Updated At</th>
                                                                    <th
                                                                        class="px-4 py-2 text-left text-xs font-medium text-gray-600">
                                                                        Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-gray-200">
                                                                @foreach ($employee->deductions as $deduction)
                                                                    <tr>
                                                                        <td class="px-4 py-2">{{ $deduction->type }}
                                                                        </td>
                                                                        <td class="px-4 py-2">{{ $deduction->rate }}
                                                                        </td>
                                                                        <td class="px-4 py-2">
                                                                            {{ $deduction->created_at->format('Y-m-d H:i') }}
                                                                        </td>
                                                                        <td class="px-4 py-2">
                                                                            {{ $deduction->updated_at->format('Y-m-d H:i') }}
                                                                        </td>
                                                                        <td class="px-4 py-2">
                                                                            <!-- Edit Form -->
                                                                            <form method="POST"
                                                                                action="{{ route('employee-deductions.update', $deduction->id) }}"
                                                                                class="inline">
                                                                                @csrf
                                                                                @method('PUT')
                                                                                <select name="type"
                                                                                    class="border px-2 py-1 starkey-w-32">
                                                                                    <option value="CASH"
                                                                                        {{ $deduction->type == 'CASH' ? 'selected' : '' }}>
                                                                                        CASH</option>
                                                                                    <option value="MISC"
                                                                                        {{ $deduction->type == 'MISC' ? 'selected' : '' }}>
                                                                                        MISC</option>
                                                                                    <option value="BANK ADV"
                                                                                        {{ $deduction->type == 'BANK ADV' ? 'selected' : '' }}>
                                                                                        BANK ADV</option>
                                                                                </select>
                                                                                <input type="number" name="rate"
                                                                                    value="{{ $deduction->rate }}"
                                                                                    step="0.01"
                                                                                    class="border px-2 py-1 starkey-w-20">
                                                                                <button
                                                                                    class="bg-yellow-500 text-white px-2 py-1 rounded">Update</button>
                                                                            </form>
                                                                            <!-- Delete Form -->
                                                                            <form method="POST"
                                                                                action="{{ route('employee-deductions.destroy', $deduction->id) }}"
                                                                                class="inline ml-2">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button
                                                                                    onclick="return confirm('Are you sure?')"
                                                                                    class="bg-red-600 text-white px-2 py-1 rounded">Delete</button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @include('partials.js')

    <!-- Tailwind DataTables via CDN -->
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.tailwindcss.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new DataTable('#employeesTable');
        });
    </script>
</body>

</html>
