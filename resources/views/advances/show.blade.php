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
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Advance Details</h2>

                <!-- Advance Details -->
                <div class="space-y-4">
                    <div>
                        <span class="font-medium text-gray-700">ID:</span>
                        <span class="text-gray-600">{{ $advance->id }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Employee:</span>
                        <span class="text-gray-600">{{ $advance->employee ? $advance->employee->name : 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Amount:</span>
                        <span class="text-gray-600">${{ number_format($advance->money, 2) }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Notes:</span>
                        <span class="text-gray-600">{{ $advance->notes ?: 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Status:</span>
                        <span class="inline-block px-2 py-1 rounded text-sm {{ $advance->status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $advance->status == 1 ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-2 mt-6">
                    <a href="{{ route('advances.edit', $advance->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">Edit</a>
                    <form action="{{ route('advances.updateStatus', $advance->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $advance->status == 1 ? 0 : 1 }}">
                        <button type="submit" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600">
                            Toggle Status
                        </button>
                    </form>
                    <form action="{{ route('advances.destroy', $advance->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this advance?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Delete</button>
                    </form>
                    <a href="{{ route('advances.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Back</a>
                </div>
            </div>
        </div>
    </div>

    @include('partials.js')