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
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-6">Create Skill Type</h2>
                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('skilltype.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 font-medium mb-2">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-custom-blue" required>
                        </div>
                        <div class="mb-4">
                            <label for="percentage" class="block text-gray-700 font-medium mb-2">Percentage</label>
                            <input type="number" name="percentage" id="percentage" value="{{ old('percentage') }}" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-custom-blue" min="0" max="100" required>
                        </div>
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('skilltype.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400 transition">Cancel</a>
                            <button type="submit" class="bg-custom-blue text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('partials.js')
</body>
</html>
