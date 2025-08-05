<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Simanis Dimas') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 h-screen overflow-hidden">
    <div class="h-screen flex">

        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-blue-900 via-sky-800 to-sky-600 text-white flex flex-col">
            <div class="p-6 flex justify-center border-b border-sky-500">
                <img src="{{ asset('images/logo-dinkominfo.png') }}" alt="Logo Kominfo" class="h-12">
            </div>
            <div class="p-4 text-xl font-bold border-b border-sky-500 text-center">
                Simanis Dimas
            </div>

            <nav class="p-4 space-y-2 text-sm flex-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded hover:bg-sky-700 transition">
                    Dashboard
                </a>

                <!-- Barang Dropdown -->
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open"
                            class="w-full text-left px-3 py-2 rounded hover:bg-sky-700 flex justify-between items-center transition">
                        Barang
                        <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transform transition-transform"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="ml-4 space-y-1 text-white">
                        <a href="{{ route('barang.index') }}" class="block px-3 py-1 hover:underline">Daftar Stok</a>
                        <a href="{{ route('barang.request') }}" class="block px-3 py-1 hover:underline">Pengajuan Barang</a>
                        <a href="{{ route('barang.history') }}" class="block px-3 py-1 hover:underline">Riwayat</a>
                    </div>
                </div>

                <!-- Device Dropdown -->
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open"
                            class="w-full text-left px-3 py-2 rounded hover:bg-sky-700 flex justify-between items-center transition">
                        Device
                        <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transform transition-transform"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="ml-4 space-y-1 text-white">
                        <a href="{{ route('devices.index') }}" class="block px-3 py-1 hover:underline">Manajemen Device</a>
                        @php
                            $userDevice = \App\Models\Device::where('user_id', auth()->id())->first();
                        @endphp

                        @if ($userDevice)
                            <a href="{{ route('devices.show', $userDevice->id) }}"
                            class="block px-3 py-1 hover:underline">Laporan Perawatan</a>
                            <a href="{{ route('device.riwayatAll', $userDevice->id) }}"
                            class="block px-3 py-1 hover:underline">Riwayat Device</a>
                        @else
                            <span class="block px-3 py-1 text-gray-400">Laporan Perawatan (belum ada device)</span>
                            <span class="block px-3 py-1 text-gray-400">Riwayat Device (belum ada device)</span>
                        @endif
                </div>

                <!-- Kendaraan -->
                        <a href="{{ route('kendaraan.index') }}" class="block px-3 py-1 hover:underline">Kendaraan</a>
            </nav>

            <!-- Admin Section -->
            <div x-data="{ open: false }" class="px-4 mb-6">
                <hr class="border-t border-sky-500 mb-4">
                <button @click="open = !open" class="w-full text-left flex justify-between items-center px-3 py-2 rounded hover:bg-sky-700 transition">
                    {{ Auth::user()->name }}
                    <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transform transition-transform"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-cloak class="mt-3 ml-2 space-y-2 text-sm text-white">
                    <div class="text-xs text-sky-200">{{ Auth::user()->email }}</div>
                    <a href="{{ route('profile.edit') }}" class="block hover:underline">Edit Profil</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="hover:underline">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Konten Utama Scrollable -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            @isset($header)
                <header class="bg-white shadow shrink-0">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="flex-1 overflow-y-auto py-6 px-4 sm:px-6 lg:px-8 bg-gray-100">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
