<!DOCTYPE html>
<html lang="en">
@include('partials.head')

<body
    class="bg-gradient-to-br from-gray-50 to-gray-200 font-sans antialiased min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Reset Password Card -->
        <div class="bg-white rounded-2xl shadow-xl transform hover:scale-[1.02] transition-all">
            <div class="p-6 md:p-8">
                <!-- Header -->
                <div class="flex justify-center mb-6">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Reset Password</h1>
                </div>

                <!-- Success/Error Messages -->
                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Reset Password Form -->
                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="mt-1 w-full p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                            placeholder="Enter your email" required>
                        @error('email')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" id="password" name="password"
                            class="mt-1 w-full p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                            placeholder="Enter your new password" required>
                        @error('password')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="mt-1 w-full p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                            placeholder="Confirm your new password" required>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-custom-blue text-white py-3 rounded-lg hover:bg-custom-blue-dark transition-all">
                        Reset Password
                    </button>
                </form>

                <!-- Back to Login -->
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm text-custom-blue hover:underline">Back to Login</a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center text-gray-600 text-sm">
            <p>Sunday, May 18, 2025 | 11:24 PM IST</p>
        </div>
    </div>
</body>
@include('partials.js')
</html>
