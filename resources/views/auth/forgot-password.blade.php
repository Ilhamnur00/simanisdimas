<x-guest-layout>
    <!-- Header -->
    <div class="text-left mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Lupa Password</h2>
        <p class="italic text-sm text-gray-500">Silakan masukkan alamat email yang terdaftar pada sistem. Kami akan mengirimkan tautan untuk pengaturan ulang kata sandi Anda.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex justify-center items-center mt-8">
            <x-primary-button class="bg-gradient-to-br from-sky-400 to-blue-600 hover:from-sky-500 hover:to-blue-700 
            text-white font-semibold py-3 px-10 rounded-md text-center transition duration-300">
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
