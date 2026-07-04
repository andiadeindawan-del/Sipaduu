<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800">
        <div class="w-full max-w-6xl bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row">
            <!-- Left Side - Visual/Brand -->
            <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-indigo-600 to-blue-700 p-12 flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-3 mb-8">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class="bi bi-grid-1x2-fill text-white text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-white text-2xl font-bold">adminHMD</h2>
                            <p class="text-indigo-200 text-sm">Administration Dashboard</p>
                        </div>
                    </div>
                    
                    <div class="space-y-6">
                        <h1 class="text-white text-4xl font-bold leading-tight">
                            Welcome Back!
                        </h1>
                        <p class="text-indigo-100 text-lg leading-relaxed">
                            Sign in to access your admin workspace and manage your business efficiently.
                        </p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-3 text-indigo-100">
                        <i class="bi bi-shield-check text-xl"></i>
                        <span class="text-sm">Secure & Encrypted Connection</span>
                    </div>
                    <div class="flex items-center space-x-3 text-indigo-100">
                        <i class="bi bi-clock-history text-xl"></i>
                        <span class="text-sm">Last login: Today at 10:30 AM</span>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="w-full md:w-1/2 p-8 sm:p-12">
                <div class="max-w-md mx-auto">
                    <!-- Mobile Brand -->
                    <div class="md:hidden flex items-center space-x-3 mb-8">
                        <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <i class="bi bi-grid-1x2-fill text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-gray-900 dark:text-white text-xl font-bold">adminHMD</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-xs">Administration Dashboard</p>
                        </div>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-6" :status="session('status')" />

                    <!-- Form Header -->
                    <div class="mb-8">
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                            {{ __('Sign In') }}
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                            {{ __('Enter your credentials to access your account') }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <x-input-label for="email" :value="__('Email Address')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-envelope text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <x-text-input id="email" 
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-150" 
                                    type="email" 
                                    name="email" 
                                    :value="old('email')" 
                                    required 
                                    autofocus 
                                    autocomplete="username" 
                                    placeholder="name@example.com" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div>
                            <div class="flex items-center justify-between">
                                <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                                @if (Route::has('password.request'))
                                    <a class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition duration-150" href="{{ route('password.request') }}">
                                        {{ __('Forgot password?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-lock text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <x-text-input id="password" 
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-150"
                                    type="password"
                                    name="password"
                                    required 
                                    autocomplete="current-password"
                                    placeholder="Enter your password" />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <label for="remember_me" class="flex items-center cursor-pointer">
                                <input id="remember_me" 
                                    type="checkbox" 
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 transition duration-150"
                                    name="remember">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Remember me') }}
                                </span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <x-primary-button class="w-full justify-center py-3 px-4 bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500/50 dark:focus:ring-indigo-400/50 rounded-xl text-white font-semibold transition duration-150">
                                <i class="bi bi-box-arrow-in-right mr-2"></i>
                                {{ __('Sign In') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <!-- Footer Links -->
                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __("Don't have an account?") }}
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition duration-150">
                                    {{ __('Create account') }}
                                </a>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>