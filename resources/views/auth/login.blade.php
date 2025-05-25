<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
    <!-- Ensure Alpine.js is included -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- Custom styles for password toggle -->
    <style>
        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>

<body
    class="bg-gradient-to-br from-gray-50 to-gray-200 font-sans antialiased min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl transform hover:scale-[1.02] transition-all">
            <div class="p-6 md:p-8">
                <!-- Header -->
                <div class="text-center mb-6">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Kunal Global</h1>
                    <p class="text-gray-900 font-extrabold">Fabtech Private Limited</p>
                </div>


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

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6" x-data="{ showPassword: false }">
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

                    <!-- Password Field with Eye Button -->
                    <div class="password-container">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input :type="showPassword ? 'text' : 'password'" id="password" name="password"
                            class="mt-1 w-full p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                            placeholder="Enter your password" required>
                        <span class="password-toggle" @click="showPassword = !showPassword"
                            :aria-label="showPassword ? 'Hide password' : 'Show password'">
                            <svg x-show="!showPassword" class="w-5 h-5 mt-6 text-gray-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPassword" class="w-5 h-5 mt-6 text-gray-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </span>
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
                    <a href="{{ route('password.request') }}" class="text-sm text-custom-blue hover:underline">Forgot
                        your password?</a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center text-gray-600 text-sm">
            <p>Sunday, May 25, 2025 | 02:24 PM IST</p>
        </div>
    </div>

    @include('partials.js')
</body>

</html>
