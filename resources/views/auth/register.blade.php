<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4 py-8 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800">
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
                            Create Account
                        </h1>
                        <p class="text-indigo-100 text-lg leading-relaxed">
                            Join adminHMD and start managing your business with our comprehensive administration tools.
                        </p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-3 text-indigo-100">
                        <i class="bi bi-shield-check text-xl"></i>
                        <span class="text-sm">Your data is secure and protected</span>
                    </div>
                    <div class="flex items-center space-x-3 text-indigo-100">
                        <i class="bi bi-people text-xl"></i>
                        <span class="text-sm">Join 1,000+ business owners</span>
                    </div>
                </div>
            </div>

            <!-- Right Side - Register Form -->
            <div class="w-full md:w-1/2 p-6 sm:p-10">
                <div class="max-w-md mx-auto">
                    <!-- Mobile Brand -->
                    <div class="md:hidden flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <i class="bi bi-grid-1x2-fill text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-gray-900 dark:text-white text-xl font-bold">adminHMD</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-xs">Administration Dashboard</p>
                        </div>
                    </div>

                    <!-- Form Header -->
                    <div class="mb-6">
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                            {{ __('Register') }}
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                            {{ __('Create your account to get started') }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <!-- NIK -->
                        <div>
                            <x-input-label for="nik" :value="__('NIK (ID Number)')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-person-badge text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <x-text-input id="nik" 
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-150" 
                                    type="text" 
                                    name="nik" 
                                    :value="old('nik')" 
                                    required 
                                    autofocus 
                                    autocomplete="off" 
                                    placeholder="Enter your NIK" />
                            </div>
                            <x-input-error :messages="$errors->get('nik')" class="mt-1.5" />
                        </div>

                        <!-- Nama -->
                        <div>
                            <x-input-label for="nama" :value="__('Full Name')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-person text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <x-text-input id="nama" 
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-150" 
                                    type="text" 
                                    name="nama" 
                                    :value="old('nama')" 
                                    required 
                                    autocomplete="name" 
                                    placeholder="Enter your full name" />
                            </div>
                            <x-input-error :messages="$errors->get('nama')" class="mt-1.5" />
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email Address')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-envelope text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <x-text-input id="email" 
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-150" 
                                    type="email" 
                                    name="email" 
                                    :value="old('email')" 
                                    required 
                                    autocomplete="username" 
                                    placeholder="name@example.com" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                        </div>

                        <!-- Kontak -->
                        <div>
                            <x-input-label for="kontak" :value="__('Contact Number')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-phone text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <x-text-input id="kontak" 
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-150" 
                                    type="text" 
                                    name="kontak" 
                                    :value="old('kontak')" 
                                    required 
                                    autocomplete="tel" 
                                    placeholder="Enter your phone number" />
                            </div>
                            <x-input-error :messages="$errors->get('kontak')" class="mt-1.5" />
                        </div>

                        <!-- Nama Usaha & NIB (2 columns on desktop) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="nama_usaha" :value="__('Business Name')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                                <div class="mt-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-building text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    <x-text-input id="nama_usaha" 
                                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-150" 
                                        type="text" 
                                        name="nama_usaha" 
                                        :value="old('nama_usaha')" 
                                        required 
                                        autocomplete="off" 
                                        placeholder="Business name" />
                                </div>
                                <x-input-error :messages="$errors->get('nama_usaha')" class="mt-1.5" />
                            </div>

                            <div>
                                <x-input-label for="nib" :value="__('NIB')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                                <div class="mt-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-card-text text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    <x-text-input id="nib" 
                                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-150" 
                                        type="text" 
                                        name="nib" 
                                        :value="old('nib')" 
                                        required 
                                        autocomplete="off" 
                                        placeholder="Business registration number" />
                                </div>
                                <x-input-error :messages="$errors->get('nib')" class="mt-1.5" />
                            </div>
                        </div>

                        <!-- Jenis Usaha -->
                        <div>
                            <x-input-label for="jenis_usaha" :value="__('Business Type')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-tag text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <select id="jenis_usaha" 
                                    name="jenis_usaha" 
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white appearance-none transition duration-150" 
                                    required>
                                    <option value="">{{ __('Select business type') }}</option>
                                    <option value="formal" @selected(old('jenis_usaha') === 'formal')>{{ __('Formal') }}</option>
                                    <option value="non_formal" @selected(old('jenis_usaha') === 'non_formal')>{{ __('Non Formal') }}</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="bi bi-chevron-down text-gray-400 dark:text-gray-500"></i>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('jenis_usaha')" class="mt-1.5" />
                        </div>

                        <!-- Alamat Lengkap -->
                        <div>
                            <x-input-label for="alamat_lengkap" :value="__('Complete Address')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                            <div class="mt-1">
                                <textarea id="alamat_lengkap" 
                                    name="alamat_lengkap" 
                                    rows="3" 
                                    class="block w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-150" 
                                    required 
                                    placeholder="Enter your complete address">{{ old('alamat_lengkap') }}</textarea>
                            </div>
                            <x-input-error :messages="$errors->get('alamat_lengkap')" class="mt-1.5" />
                        </div>

                        <!-- Password & Confirm Password (2 columns on desktop) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                                <div class="mt-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-lock text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    <x-text-input id="password" 
                                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-150"
                                        type="password"
                                        name="password"
                                        required 
                                        autocomplete="new-password"
                                        placeholder="Min 8 characters" />
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                                <div class="mt-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-lock-fill text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    <x-text-input id="password_confirmation" 
                                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-150"
                                        type="password"
                                        name="password_confirmation" 
                                        required 
                                        autocomplete="new-password"
                                        placeholder="Confirm your password" />
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <x-primary-button class="w-full justify-center py-3 px-4 bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500/50 dark:focus:ring-indigo-400/50 rounded-xl text-white font-semibold transition duration-150">
                                <i class="bi bi-person-plus mr-2"></i>
                                {{ __('Create Account') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <!-- Footer Links -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Already have an account?') }}
                            <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition duration-150">
                                {{ __('Sign in') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>