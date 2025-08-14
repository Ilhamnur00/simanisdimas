<section class="bg-white rounded-2xl shadow p-6 space-y-6">
    <header class="mb-4">
        <h2 class="text-xl font-bold text-sky-800">
            {{ __('Perbarui Kata Sandi') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk menjaga keamanan.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        {{-- Kata Sandi Saat Ini --}}
        <div>
            <x-input-label for="update_password_current_password" :value="__('Kata Sandi Saat Ini')" class="text-sky-700 font-medium" />
            <x-text-input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="mt-1 block w-full border border-sky-300 rounded-lg shadow-sm focus:ring focus:ring-sky-300/50 focus:border-sky-500 transition"
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        {{-- Kata Sandi Baru --}}
        <div>
            <x-input-label for="update_password_password" :value="__('Kata Sandi Baru')" class="text-sky-700 font-medium" />
            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                class="mt-1 block w-full border border-sky-300 rounded-lg shadow-sm focus:ring focus:ring-sky-300/50 focus:border-sky-500 transition"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        {{-- Konfirmasi Kata Sandi --}}
        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="text-sky-700 font-medium" />
            <x-text-input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-1 block w-full border border-sky-300 rounded-lg shadow-sm focus:ring focus:ring-sky-300/50 focus:border-sky-500 transition"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Tombol Simpan --}}
        <div class="flex items-center gap-4">
            <x-primary-button
                class="bg-gradient-to-r from-sky-700 to-sky-500 hover:from-sky-800 hover:to-sky-600 text-white font-semibold px-4 py-2 rounded shadow transition duration-200">
                {{ __('Simpan') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-medium"
                >
                    {{ __('Tersimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>
