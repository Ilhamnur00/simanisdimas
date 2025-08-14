<section class="space-y-6">
    <header class="mb-4">
        <h2 class="text-xl font-bold text-sky-800">
            {{ __('Informasi Profil') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Perbarui informasi profil dan alamat email akun Anda.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        {{-- Field Nama --}}
        <div>
            <x-input-label for="name" :value="__('Nama')" class="text-sky-700 font-medium" />
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="mt-1 block w-full border border-sky-300 rounded-lg shadow-sm focus:ring focus:ring-sky-300/50 focus:border-sky-500 transition"
                :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Field Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-sky-700 font-medium" />
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-1 block w-full border border-sky-300 rounded-lg shadow-sm focus:ring focus:ring-sky-300/50 focus:border-sky-500 transition"
                :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-gray-800">
                        {{ __('Alamat email Anda belum terverifikasi.') }}
                        <button
                            form="send-verification"
                            class="underline text-sm text-sky-600 hover:text-sky-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 rounded-md transition">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Tombol & Notifikasi --}}
        <div class="flex items-center gap-4">
            <x-primary-button
                class="bg-gradient-to-r from-sky-700 to-sky-500 hover:from-sky-800 hover:to-sky-600 text-white font-semibold px-4 py-2 rounded shadow transition duration-200">
                {{ __('Simpan') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
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
