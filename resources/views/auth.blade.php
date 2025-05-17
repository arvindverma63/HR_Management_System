<!DOCTYPE html>
<html lang="en">
@include('partials.head')

<body
    class="bg-gradient-to-br from-gray-50 to-gray-200 font-sans antialiased min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl transform hover:scale-[1.02] transition-all">
            <div class="p-6 md:p-8">
                <!-- Header -->
                <div class="flex justify-center mb-6">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Admin Dashboard</h1>
                </div>

                <!-- Login Form -->
                <form method="get" action="/new-workmen" class="space-y-6">
                    @csrf

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
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="password" name="password"
                            class="mt-1 w-full p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                            placeholder="Enter your password" required>
                        @error('password')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                            class="h-4 w-4 text-custom-blue focus:ring-custom-blue border-gray-300 rounded">
                        <label for="remember" class="ml-2 text-sm text-gray-700">Remember me</label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-custom-blue text-white py-3 rounded-lg hover:bg-custom-blue-dark transition-all">Login</button>
                </form>

                <!-- Additional Links -->
                <div class="mt-6 text-center">
                    <a href="" class="text-sm text-custom-blue hover:underline">Forgot your password?</a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center text-gray-600 text-sm">
            <p>Friday, May 16, 2025 | 11:30 PM IST</p>
        </div>
    </div>
</body>
@include('partials.js')

</html>
