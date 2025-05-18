<!DOCTYPE html>
<html lang="en">
@include('partials.head')
<body class="bg-gradient-to-br from-gray-50 to-gray-200 font-sans antialiased">
  <div class="flex min-h-screen">
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 p-4 md:p-8 w-full">
      <!-- Mobile Menu Button -->
      <button id="open-sidebar" class="md:hidden fixed top-4 left-4 z-30 bg-custom-blue text-white p-2 rounded-lg focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      @include('partials.header')

      <!-- Dashboard Section -->
      <section id="dashboard">
        <div class="space-y-8">
          <!-- Summary Cards -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Employees -->
            <div class="bg-white p-6 rounded-2xl shadow-lg transform hover:scale-[1.02] transition-all">
              <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Employees</h3>
              <p class="text-3xl font-bold text-custom-blue">{{ $totalEmployees }}</p>
              <p class="text-sm text-gray-500 mt-1">
                @if($employeesThisMonth > 0)
                    +{{ $employeesThisMonth }} this month
                @else
                    No new employees this month
                @endif
              </p>
            </div>
            <!-- Total Locations -->
            <div class="bg-white p-6 rounded-2xl shadow-lg transform hover:scale-[1.02] transition-all">
              <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Locations</h3>
              <p class="text-3xl font-bold text-custom-blue">{{ $totalLocations }}</p>
              <p class="text-sm text-gray-500 mt-1">
                @if($lastLocation)
                    Last added: {{ $lastLocation->name }}
                @else
                    No locations added yet
                @endif
              </p>
            </div>
            <!-- Today's Attendance -->
            <div class="bg-white p-6 rounded-2xl shadow-lg transform hover:scale-[1.02] transition-all">
              <h3 class="text-lg font-semibold text-gray-800 mb-2">Today's Attendance</h3>
              <div class="flex space-x-4">
                <div>
                  <p class="text-sm text-gray-600">Present</p>
                  <p class="text-xl font-bold text-green-600">{{ $attendance['present'] }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Absent</p>
                  <p class="text-xl font-bold text-red-600">{{ $attendance['absent'] }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Leave</p>
                  <p class="text-xl font-bold text-yellow-600">{{ $attendance['leave'] }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Quick Links -->
          <div class="bg-white p-6 rounded-2xl shadow-lg">
            <h3 class="text-xl md:text-2xl font-semibold text-gray-800 mb-4 md:mb-6">Quick Links</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
              <a href="{{ route('new-workmen') }}" class="bg-custom-blue text-white text-center py-3 rounded-lg hover:bg-custom-blue-dark transition-all">New Workmen Form</a>
              <a href="{{ route('attendence') }}" class="bg-custom-blue text-white text-center py-3 rounded-lg hover:bg-custom-blue-dark transition-all">Take Attendance</a>
              <a href="{{ route('reports') }}" class="bg-custom-blue text-white text-center py-3 rounded-lg hover:bg-custom-blue-dark transition-all">Reports</a>
              <a href="{{ route('locations') }}" class="bg-custom-blue text-white text-center py-3 rounded-lg hover:bg-custom-blue-dark transition-all">Locations</a>
            </div>
          </div>

          <!-- Recent Activity Log -->
          <div class="bg-white p-6 rounded-2xl shadow-lg">
            <h3 class="text-xl md:text-2xl font-semibold text-gray-800 mb-4 md:mb-6">Recent Activity</h3>
            <div class="overflow-x-auto">
              <table class="w-full text-left text-sm md:text-base">
                <thead>
                  <tr class="bg-custom-blue text-white">
                    <th class="p-2 md:p-4">Action</th>
                    <th class="p-2 md:p-4">Details</th>
                    <th class="p-2 md:p-4">User</th>
                    <th class="p-2 md:p-4">Date</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($recentActivities as $activity)
                    <tr class="border-b hover:bg-gray-50 transition-all">
                      <td class="p-2 md:p-4">{{ $activity->action }}</td>
                      <td class="p-2 md:p-4">{{ $activity->details }}</td>
                      <td class="p-2 md:p-4">{{ $activity->user }}</td>
                      <td class="p-2 md:p-4">{{ $activity->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" class="p-2 md:p-4 text-center text-gray-500">No recent activities.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>

  @include('partials.js')
</body>
</html>
