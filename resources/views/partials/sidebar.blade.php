 <div id="sidebar"
     class="fixed inset-y-0 w-64 bg-custom-blue text-white flex flex-col shadow-xl md:static md:w-72 z-20">
     <div class="p-4 md:p-6 flex justify-between items-center">
         <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">HR Dashboard</h1>
         <button id="close-sidebar" class="md:hidden text-white focus:outline-none">
             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
             </svg>
         </button>
     </div>
     <nav class="flex-1">
         <a href="{{ route('dashboard') }}"
             class="block py-2 px-4 md:py-3 md:px-6 text-base md:text-lg hover:bg-custom-blue-dark transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-950' : '' }}">Dashboard</a>
         <a href="{{ route('new-workmen') }}"
             class="block py-2 px-4 md:py-3 md:px-6 text-base md:text-lg hover:bg-custom-blue-dark transition-all {{ request()->routeIs('new-workmen') ? 'bg-blue-950' : '' }}">New
             Workmen Form</a>
         <a href="{{ route('workmen') }}"
             class="block py-2 px-4 md:py-3 md:px-6 text-base md:text-lg hover:bg-custom-blue-dark transition-all {{ request()->routeIs('workmen') ? 'bg-blue-950' : '' }}">Workmen</a>
         <a href="{{ route('attendence') }}"
             class="block py-2 px-4 md:py-3 md:px-6 text-base md:text-lg hover:bg-custom-blue-dark transition-all {{ request()->routeIs('attendence') ? 'bg-blue-950' : '' }}">Take
             Attendance</a>
         <a href="{{ route('reports') }}"
             class="block py-2 px-4 md:py-3 md:px-6 text-base md:text-lg hover:bg-custom-blue-dark transition-all {{ request()->routeIs('reports') ? 'bg-blue-950' : '' }}">Reports</a>
         <a href="{{ route('locations') }}"
             class="block py-2 px-4 md:py-3 md:px-6 text-base md:text-lg hover:bg-custom-blue-dark transition-all {{ request()->routeIs('locations') ? 'bg-blue-950' : '' }}">Locations</a>
     </nav>

     <div class="p-4 md:p-6">
         <button class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition-all">Logout</button>
     </div>
 </div>
