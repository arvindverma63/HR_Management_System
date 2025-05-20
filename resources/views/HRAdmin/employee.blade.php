<!DOCTYPE html>
<html lang="en">
@include('partials.head')

<body class="bg-gradient-to-br from-gray-50 to-gray-200 font-sans antialiased">
    <div class="flex min-h-screen">
        @include('HRAdmin.partials.sidebar')

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

            <!-- Workmen Section -->
            <section id="workmen">
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg">
                    <h3 class="text-xl md:text-2xl font-semibold text-gray-800 mb-4 md:mb-6">Employees List</h3>

                    <!-- Success/Error Messages -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Search Bar -->
                    <div class="mb-4 md:mb-6">
                        <form method="GET" action="{{ route('workmen') }}">
                            <input type="text" id="search" name="search" value="{{ $search }}"
                                class="w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                placeholder="Search workmen by name or department..."
                                oninput="this.form.submit()">
                        </form>
                    </div>

                    <!-- Workmen Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm md:text-base">
                            <thead>
                                <tr class="bg-custom-blue text-white">
                                    <th class="p-2 md:p-4">Name</th>
                                    <th class="p-2 md:p-4">Department</th>
                                    <th class="p-2 md:p-4">Location</th>
                                    <th class="p-2 md:p-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($workmen as $workman)
                                    <tr class="border-b hover:bg-gray-50 transition-all">
                                        <td class="p-2 md:p-4">{{ $workman->name }} {{ $workman->surname }}</td>
                                        <td class="p-2 md:p-4">{{ $workman->designation ?? 'N/A' }}</td>
                                        <td class="p-2 md:p-4">{{ $workman->location->name ?? 'N/A' }}</td>
                                        <td class="p-2 md:p-4 flex space-x-2">
                                            <a href="{{ route('employee.edit', $workman) }}"
                                                class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all">Edit</a>
                                            <form action="{{ route('employee.destroy', $workman) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this workman?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all">Delete</button>
                                            </form>
                                            <a href="{{ route('employee.download-pdf', $workman) }}"
                                                class="px-3 py-1 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all">Download PDF</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-2 md:p-4 text-center text-gray-500">No Employee found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mt-4 md:mt-6 space-y-4 md:space-y-0">
                        <div>
                            <span class="text-gray-600 text-sm md:text-base">
                                Showing {{ $workmen->firstItem() }} to {{ $workmen->lastItem() }} of {{ $workmen->total() }} entries
                            </span>
                        </div>
                        <div class="flex space-x-2">
                            {{ $workmen->appends(['search' => $search])->links('pagination::tailwind') }}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @include('partials.js')
</body>
</html>
