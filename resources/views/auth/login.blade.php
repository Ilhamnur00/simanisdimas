<x-guest-layout>
    <!-- Header -->
    <div class="text-left mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Log In</h2>
        <p class="italic text-sm text-gray-500">Silakan masuk untuk mulai mengelola data barang dan aset secara efisien</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input 
                id="email"
                type="email"
                name="email"
                :value="old('email')"
                required autofocus autocomplete="username"
                class="block w-full border-0 border-b border-white-300 focus:border-blue-500 focus:ring-0 rounded-none"

            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input 
                id="password"
                type="password"
                name="password"
                required autocomplete="current-password"
                class="block w-full border-0 border-b border-gray-300 focus:border-blue-500 focus:ring-0 rounded-none"

            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-600 hover:text-indigo-800 underline" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <!-- Submit -->
        <div class="w-full mt-4">
            <x-primary-button class="w-full flex justify-center items-center bg-gradient-to-br from-sky-400 to-blue-600 
                hover:from-sky-500 hover:to-blue-700 text-white font-semibold py-3 rounded-md text-center transition duration-300">
                {{ __('LOGIN') }}
            </x-primary-button>
        </div>
        </div>
    </form>
</x-guest-layout>
