<!DOCTYPE html>
<html lang="en">

@include('partials.head')

<body class="bg-gradient-to-br from-gray-100 to-gray-300 font-sans antialiased min-h-screen overflow-hidden">
    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <!-- Mobile Menu Button -->
            <button id="open-sidebar"
                class="md:hidden fixed top-4 left-4 z-30 bg-custom-blue text-white p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-custom-blue transition"
                aria-label="Open sidebar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 w-full">
                @include('partials.header')

                <div class="bg-white rounded-2xl shadow-xl p-4 sm:p-5 md:p-6 lg:p-8 mt-6">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6">Skill Type</h2>


                </div>
            </div>
        </div>
    </div>


    @include('partials.js')
</body>

</html>
