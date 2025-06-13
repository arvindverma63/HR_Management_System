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


            <!-- Search Section -->
            <div class="max-w-xl mx-auto my-8 bg-white shadow rounded p-6">
                <h2 class="text-2xl font-bold mb-4">Find Employee</h2>
                <form method="GET" action="{{ route('employee-deductions') }}" class="flex gap-4">
                    <input type="text" name="employee_unique_id" placeholder="Enter Employee Unique ID"
                        value="{{ request('employee_unique_id') }}"
                        class="flex-1 border rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                        Search
                    </button>
                </form>
            </div>

            @if ($employee)
                <!-- Employee Details -->
                <div class="max-w-xl mx-auto bg-white shadow rounded p-6 mb-8">
                    <h3 class="text-xl font-semibold mb-2">Employee Details</h3>
                    <p><strong>Unique ID:</strong> {{ $employee->employee_unique_id }}</p>
                    <p><strong>Name:</strong> {{ $employee->name ?? 'N/A' }}</p>
                    <!-- Add more if needed -->
                </div>

                <!-- Add Deduction Form -->
                <div class="max-w-xl mx-auto bg-white shadow rounded p-6 mb-8">
                    <h3 class="text-xl font-semibold mb-4">Add Deduction</h3>
                    <form method="POST" action="{{ route('employee-deductions.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="employee_unique_id" value="{{ $employee->employee_unique_id }}">
                        <div>
                            <label class="block mb-1 font-medium">Type</label>
                            <select name="type" required
                                class="w-full border rounded px-4 py-2 focus:outline-none focus:ring">
                                <option value="">Select Type</option>
                                <option value="CASH">CASH</option>
                                <option value="MISC">MISC</option>
                                <option value="BANK ADV">BANK ADV</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 font-medium">Rate</label>
                            <input type="number" name="rate" step="0.01" required
                                class="w-full border rounded px-4 py-2 focus:outline-none focus:ring">
                        </div>
                        <button type="submit"
                            class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">
                            Add Deduction
                        </button>
                    </form>
                </div>

                <!-- Deduction Table -->
                <div class="max-w-4xl mx-auto bg-white shadow rounded p-6">
                    <h3 class="text-xl font-semibold mb-4">Deductions</h3>
                    <div class="overflow-x-auto w-full">
                        <table class="min-w-full border divide-y divide-gray-200" id="deductionsTable">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Type</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Rate</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Created At</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Updated At</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($deductions as $deduction)
                                    <tr>
                                        <td class="px-4 py-2">{{ $deduction->type }}</td>
                                        <td class="px-4 py-2">{{ $deduction->rate }}</td>
                                        <td class="px-4 py-2">{{ $deduction->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="px-4 py-2">{{ $deduction->updated_at->format('Y-m-d H:i') }}</td>
                                        <td class="px-4 py-2">
                                            <form method="POST"
                                                action="{{ route('employee-deductions.update', $deduction->id) }}"
                                                class="inline">
                                                @csrf
                                                @method('PUT')
                                                <select name="type" class="border px-2 py-1 w-32">
                                                    <option value="CASH"
                                                        {{ $deduction->type == 'CASH' ? 'selected' : '' }}>CASH
                                                    </option>
                                                    <option value="MISC"
                                                        {{ $deduction->type == 'MISC' ? 'selected' : '' }}>MISC
                                                    </option>
                                                    <option value="BANK ADV"
                                                        {{ $deduction->type == 'BANK ADV' ? 'selected' : '' }}>BANK ADV
                                                    </option>
                                                </select>
                                                <input type="number" name="rate" value="{{ $deduction->rate }}"
                                                    class="border px-2 py-1 w-20">
                                                <button
                                                    class="bg-yellow-500 text-white px-2 py-1 rounded">Update</button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('employee-deductions.destroy', $deduction->id) }}"
                                                class="inline ml-2">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Are you sure?')"
                                                    class="bg-red-600 text-white px-2 py-1 rounded">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            @endif

        </div>
    </div>

    @include('partials.js')

    <!-- Tailwind DataTables via CDN -->
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.tailwindcss.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new DataTable('#deductionsTable');
        });
    </script>
</body>

</html>
