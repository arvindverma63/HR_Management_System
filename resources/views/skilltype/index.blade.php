<!DOCTYPE html>
<html lang="en">
@include('partials.head')
<body class="bg-gradient-to-br from-gray-100 to-gray-300 font-sans antialiased min-h-screen overflow-hidden">
    <div class="flex h-screen">
        @include('partials.sidebar')
        <div class="flex-1 flex flex-col overflow-y-auto">
            <button id="open-sidebar" class="md:hidden fixed top-4 left-4 z-30 bg-custom-blue text-white p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-custom-blue transition" aria-label="Open sidebar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 w-full">
                @include('partials.header')
                <div class="bg-white rounded-2xl shadow-xl p-4 sm:p-5 md:p-6 lg:p-8 mt-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Skill Types</h2>
                        <a href="{{ route('skilltype.create') }}" class="bg-custom-blue text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Add New Skill Type</a>
                    </div>
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 text-left">Name</th>
                                    <th class="px-4 py-2 text-left">Percentage</th>
                                    <th class="px-4 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($skillTypes as $skillType)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $skillType->name }}</td>
                                        <td class="px-4 py-2">{{ $skillType->percentage }}%</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('skilltype.edit', $skillType) }}" class="text-blue-600 hover:underline">Edit</a>
                                            <form action="{{ route('skilltype.destroy', $skillType) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this skill type?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline ml-4">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials.js')
</body>
</html>
