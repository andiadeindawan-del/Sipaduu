<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Personal Information -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Personal Information') }}</h3>

            <div>
                <x-input-label for="nama" :value="__('Full Name')" />
                <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama', $user->nama)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('nama')" />
            </div>

            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <x-input-label for="kontak" :value="__('Contact Number')" />
                <x-text-input id="kontak" name="kontak" type="text" class="mt-1 block w-full" :value="old('kontak', $user->kontak)" autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('kontak')" />
            </div>
        </div>

        <!-- Business Information -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Business Information') }}</h3>

            <div>
                <x-input-label for="nama_usaha" :value="__('Business Name')" />
                <x-text-input id="nama_usaha" name="nama_usaha" type="text" class="mt-1 block w-full" :value="old('nama_usaha', $user->nama_usaha)" />
                <x-input-error class="mt-2" :messages="$errors->get('nama_usaha')" />
            </div>

            <div class="mt-4">
                <x-input-label for="nib" :value="__('NIB (Business Registration Number)')" />
                <x-text-input id="nib" name="nib" type="text" class="mt-1 block w-full" :value="old('nib', $user->nib)" />
                <x-input-error class="mt-2" :messages="$errors->get('nib')" />
            </div>

            <div class="mt-4">
                <x-input-label for="jenis_usaha" :value="__('Business Type')" />
                <select id="jenis_usaha" name="jenis_usaha" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="">{{ __('Select business type') }}</option>
                    <option value="formal" @selected(old('jenis_usaha', $user->jenis_usaha) === 'formal')>{{ __('Formal') }}</option>
                    <option value="non_formal" @selected(old('jenis_usaha', $user->jenis_usaha) === 'non_formal')>{{ __('Non Formal') }}</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('jenis_usaha')" />
            </div>

            <div class="mt-4">
                <x-input-label for="alamat_lengkap" :value="__('Complete Address')" />
                <textarea id="alamat_lengkap" name="alamat_lengkap" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('alamat_lengkap', $user->alamat_lengkap) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('alamat_lengkap')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
