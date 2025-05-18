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
      <!-- Locations Section -->
      <section id="locations">
        <div class="space-y-8">
          <!-- Add Location Form -->
          <div class="bg-white p-6 rounded-2xl shadow-lg transform hover:scale-[1.01] transition-all">
            <h3 class="text-xl md:text-2xl font-semibold text-gray-800 mb-4 md:mb-6">Add New Location</h3>
            <form method="POST" action="" class="grid grid-cols-1 gap-4 md:gap-6">
              @csrf
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div>
                  <label for="location-name" class="block text-sm font-medium text-gray-700">Location Name</label>
                  <input type="text" id="location-name" name="name" class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all" placeholder="Enter location name" required>
                  @error('name')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                  @enderror
                </div>
                <div>
                  <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                  <input type="text" id="address" name="address" class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all" placeholder="Enter address" required>
                  @error('address')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                  @enderror
                </div>
                <div>
                  <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                  <input type="text" id="city" name="city" class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all" placeholder="Enter city" required>
                  @error('city')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                  @enderror
                </div>
                <div>
                  <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                  <input type="text" id="state" name="state" class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all" placeholder="Enter state" required>
                  @error('state')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                  @enderror
                </div>
                <div>
                  <label for="contact-number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                  <input type="tel" id="contact-number" name="contact_number" class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all" placeholder="Enter contact number" required>
                  @error('contact_number')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <button type="submit" class="w-full bg-custom-blue text-white py-2 md:py-3 rounded-lg hover:bg-custom-blue-dark transition-all">Add Location</button>
            </form>
          </div>

          <!-- Locations Table -->
          <div class="bg-white p-6 rounded-2xl shadow-lg">
            <h3 class="text-xl md:text-2xl font-semibold text-gray-800 mb-4 md:mb-6">Location List</h3>
            <div class="overflow-x-auto">
              <table class="w-full text-left text-sm md:text-base">
                <thead>
                  <tr class="bg-custom-blue text-white">
                    <th class="p-2 md:p-4">Location Name</th>
                    <th class="p-2 md:p-4">Address</th>
                    <th class="p-2 md:p-4">City</th>
                    <th class="p-2 md:p-4">State</th>
                    <th class="p-2 md:p-4">Contact Number</th>
                    <th class="p-2 md:p-4">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Dummy Data -->
                  <tr class="border-b hover:bg-gray-50 transition-all">
                    <td class="p-2 md:p-4">Main Office</td>
                    <td class="p-2 md:p-4">123 Business Road</td>
                    <td class="p-2 md:p-4">Mumbai</td>
                    <td class="p-2 md:p-4">Maharashtra</td>
                    <td class="p-2 md:p-4">+91 9876543210</td>
                    <td class="p-2 md:p-4 flex space-x-2">
                      <button class="px-3 py-1 bg-custom-blue text-white rounded-lg hover:bg-custom-blue-dark transition-all">Edit</button>
                      <button class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all">Delete</button>
                    </td>
                  </tr>
                  <tr class="border-b hover:bg-gray-50 transition-all">
                    <td class="p-2 md:p-4">Branch Office</td>
                    <td class="p-2 md:p-4">456 Corporate Avenue</td>
                    <td class="p-2 md:p-4">Delhi</td>
                    <td class="p-2 md:p-4">Delhi</td>
                    <td class="p-2 md:p-4">+91 9123456789</td>
                    <td class="p-2 md:p-4 flex space-x-2">
                      <button class="px-3 py-1 bg-custom-blue text-white rounded-lg hover:bg-custom-blue-dark transition-all">Edit</button>
                      <button class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all">Delete</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>

</body>

@include('partials.js')
</html>
