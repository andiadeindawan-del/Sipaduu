<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('Create Account') }}</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Register with your details below') }}</p>
        </div>

        <!-- NIK -->
        <div>
            <x-input-label for="nik" :value="__('NIK (ID Number)')" />
            <x-text-input id="nik" class="block mt-1 w-full" type="text" name="nik" :value="old('nik')" required autofocus autocomplete="off" placeholder="Enter your NIK" />
            <x-input-error :messages="$errors->get('nik')" class="mt-2" />
        </div>

        <!-- Nama -->
        <div class="mt-4">
            <x-input-label for="nama" :value="__('Full Name')" />
            <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama" :value="old('nama')" required autocomplete="name" placeholder="Enter your full name" />
            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Enter your email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Kontak -->
        <div class="mt-4">
            <x-input-label for="no_telepon" :value="__('Contact Number')" />
            <x-text-input id="no_telepon" class="block mt-1 w-full" type="text" name="no_telepon" :value="old('no_telepon')" required autocomplete="tel" placeholder="Enter your phone number" />
            <x-input-error :messages="$errors->get('no_telepon')" class="mt-2" />
        </div>

        <!-- Nama Usaha -->
        <div class="mt-4">
            <x-input-label for="nama_usaha" :value="__('Business Name')" />
            <x-text-input id="nama_usaha" class="block mt-1 w-full" type="text" name="nama_usaha" :value="old('nama_usaha')" required autocomplete="off" placeholder="Enter your business name" />
            <x-input-error :messages="$errors->get('nama_usaha')" class="mt-2" />
        </div>

        <!-- NIB -->
        <div class="mt-4">
            <x-input-label for="nib" :value="__('NIB (Business Registration Number)')" />
            <x-text-input id="nib" class="block mt-1 w-full" type="text" name="nib" :value="old('nib')" required autocomplete="off" placeholder="Enter your NIB" />
            <x-input-error :messages="$errors->get('nib')" class="mt-2" />
        </div>

        <!-- Jenis Usaha -->
        <div class="mt-4">
            <x-input-label for="jenis_usaha" :value="__('Business Type')" />
            <select id="jenis_usaha" name="jenis_usaha" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                <option value="">{{ __('Select business type') }}</option>
                <option value="formal" @selected(old('jenis_usaha') === 'formal')>{{ __('Formal') }}</option>
                <option value="non_formal" @selected(old('jenis_usaha') === 'non_formal')>{{ __('Non Formal') }}</option>
            </select>
            <x-input-error :messages="$errors->get('jenis_usaha')" class="mt-2" />
        </div>

        <!-- Alamat Lengkap -->
        <div class="mt-4">
            <x-input-label for="alamat_lengkap" :value="__('Complete Address')" />
            <textarea id="alamat_lengkap" name="alamat_lengkap" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required placeholder="Enter your complete address">{{ old('alamat_lengkap') }}</textarea>
            <x-input-error :messages="$errors->get('alamat_lengkap')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password"
                            placeholder="Enter password (min 8 characters)" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="Confirm your password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already have an account?') }}
            </a>

            <x-primary-button>
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
