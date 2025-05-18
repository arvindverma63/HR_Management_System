<!DOCTYPE html>
<html lang="en">
@include('partials.head')

<body class="bg-gradient-to-br from-gray-50 to-gray-200 font-sans antialiased min-h-screen">
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

            <!-- Profile Update Section -->
            <section id="profile">
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg">
                    <h3 class="text-xl md:text-2xl font-semibold text-gray-800 mb-4 md:mb-6">Update Profile</h3>

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

                    <!-- Update Email Form -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Update Email</h4>
                        <form method="POST" action="{{ route('profile.update-email') }}" class="space-y-6">
                            @csrf
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">New Email Address</label>
                                <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                    class="mt-1 w-full p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    placeholder="Enter your new email" required>
                                @error('email')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="current_password_email" class="block text-sm font-medium text-gray-700">Current Password</label>
                                <input type="password" id="current_password_email" name="current_password"
                                    class="mt-1 w-full p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    placeholder="Enter your current password" required>
                                @error('current_password')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit"
                                class="w-full bg-custom-blue text-white py-3 rounded-lg hover:bg-custom-blue-dark transition-all">
                                Update Email
                            </button>
                        </form>
                    </div>

                    <!-- Update Password Form -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Update Password</h4>
                        <form method="POST" action="{{ route('profile.update-password') }}" class="space-y-6">
                            @csrf
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                                <input type="password" id="current_password" name="current_password"
                                    class="mt-1 w-full p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    placeholder="Enter your current password" required>
                                @error('current_password')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                                <input type="password" id="new_password" name="new_password"
                                    class="mt-1 w-full p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    placeholder="Enter your new password" required>
                                @error('new_password')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                    class="mt-1 w-full p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    placeholder="Confirm your new password" required>
                            </div>
                            <button type="submit"
                                class="w-full bg-custom-blue text-white py-3 rounded-lg hover:bg-custom-blue-dark transition-all">
                                Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @include('partials.js')
</body>
</html>
