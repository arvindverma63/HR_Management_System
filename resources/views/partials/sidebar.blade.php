 <div id="sidebar"
     class="fixed inset-y-0 w-64 bg-custom-blue text-white flex flex-col shadow-xl md:static md:w-72 z-20">
     <div class="p-4 md:p-6 flex justify-between items-center">
         <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Kunal Global</h1>
         <button id="close-sidebar" class="md:hidden text-white focus:outline-none">
             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
             </svg>
         </button>
     </div>
     <nav class="flex-1">

         @if (Auth::user()->role === 'admin')
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
             <a href="{{ route('locations.index') }}"
                 class="block py-2 px-4 md:py-3 md:px-6 text-base md:text-lg hover:bg-custom-blue-dark transition-all {{ request()->routeIs('locations.index') ? 'bg-blue-950' : '' }}">Locations</a>
             <a href="{{ route('designation.index') }}"
                 class="block py-2 px-4 md:py-3 md:px-6 text-base md:text-lg hover:bg-custom-blue-dark transition-all {{ request()->routeIs('designation.index') ? 'bg-blue-950' : '' }}">Designation</a>
             <a href="{{ route('employee.index') }}"
                 class="block py-2 px-4 md:py-3 md:px-6 text-base md:text-lg hover:bg-custom-blue-dark transition-all {{ request()->routeIs('employee.index') ? 'bg-blue-950' : '' }}">Employee</a>
             <a href="{{ route('hr.report') }}"
                 class="block py-2 px-4 md:py-3 md:px-6 text-base md:text-lg hover:bg-custom-blue-dark transition-all {{ request()->routeIs('hr.report') ? 'bg-blue-950' : '' }}">Employee Internal Sheet</a>
             <a href="{{ route('profile') }}"
                 class="block py-2 px-4 md:py-3 md:px-6 text-base md:text-lg hover:bg-custom-blue-dark transition-all {{ request()->routeIs('profile') ? 'bg-blue-950' : '' }}">Profile</a>

             <form method="POST" action="{{ route('logout') }}">
                 @csrf
                 <button type="submit"
                     class="block py-2 px-4 md:py-3 md:px-6 text-base md:text-lg hover:bg-custom-blue-dark transition-all">
                     Logout
                 </button>
             </form>
         @endif

         @if (Auth::user()->role === 'hr')
             <a href="{{ route('employee.index') }}"
                 class="block py-2 px-4 md:py-3 md:px-6 text-base md:text-lg hover:bg-custom-blue-dark transition-all {{ request()->routeIs('employee.index') ? 'bg-blue-950' : '' }}">Employee</a>
             <a href="{{ route('new-employee') }}"
                 class="block py-2 px-4 md:py-3 md:px-6 text-base md:text-lg hover:bg-custom-blue-dark transition-all {{ request()->routeIs('new-employee') ? 'bg-blue-950' : '' }}">New
                 Employee</a>
             <a href="{{ route('EmployeeAttendence') }}"
                 class="block py-2 px-4 md:py-3 md:px-6 text-base md:text-lg hover:bg-custom-blue-dark transition-all {{ request()->routeIs('EmployeeAttendence') ? 'bg-blue-950' : '' }}">Take
                 Attendence</a>

             <a href="{{ route('profile') }}"
                 class="block py-2 px-4 md:py-3 md:px-6 text-base md:text-lg hover:bg-custom-blue-dark transition-all {{ request()->routeIs('profile') ? 'bg-blue-950' : '' }}">Profile</a>

             <form method="POST" action="{{ route('logout') }}">
                 @csrf
                 <button type="submit"
                     class="block py-2 px-4 md:py-3 md:px-6 text-base md:text-lg hover:bg-custom-blue-dark transition-all">
                     Logout
                 </button>
             </form>
         @endif
     </nav>

 </div>
