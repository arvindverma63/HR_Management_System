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
            <div class="bg-white rounded-lg shadow-md p-6 max-w-lg mx-auto">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Create Advance</h2>

                <!-- Alerts -->
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Location and Employee Filter Form -->
                <form action="{{ route('advances.create') }}" method="GET" id="filter-form" class="mb-4">
                    <div class="mb-4">
                        <label for="location_id" class="block text-gray-700 font-medium mb-2">Location</label>
                        <select name="location_id" id="location_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-custom-blue"
                            onchange="this.form.submit()">
                            <option value="">Select Location</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}"
                                    {{ old('location_id', $location_id) == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="employee_search" class="block text-gray-700 font-medium mb-2">Search
                            Employee</label>
                        <div class="flex">
                            <input type="text" name="employee_search" id="employee_search"
                                value="{{ old('employee_search', $employee_search) }}" placeholder="Search by id..."
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-custom-blue">
                            <button type="submit"
                                class="ml-2 bg-custom-blue text-white px-4 py-2 rounded-lg hover:bg-blue-700">Search</button>
                        </div>
                    </div>
                </form>

                <!-- Create Advance Form -->
                <form action="{{ route('advances.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="employee_id" class="block text-gray-700 font-medium mb-2">Employee</label>
                        <select name="employee_id" id="employee_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-custom-blue"
                            required>
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="money" class="block text-gray-700 font-medium mb-2">Amount</label>
                        <input type="number" name="money" id="money" value="{{ old('money') }}" step="0.01"
                            min="0"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-custom-blue"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="block text-gray-700 font-medium mb-2">Notes</label>
                        <textarea name="notes" id="notes"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-custom-blue">{{ old('notes') }}</textarea>
                    </div>


                    @if (Auth::user()->role === 'admin')
                        <div class="mb-4">
                            <label for="status" class="block text-gray-700 font-medium mb-2">Status</label>
                            <select name="status" id="status"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-custom-blue">
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    @endif

                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('advances.index') }}"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Cancel</a>
                        <button type="submit" class="bg-custom-blue text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Create Advance
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('partials.js')
