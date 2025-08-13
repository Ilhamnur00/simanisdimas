<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Simanis Dimas') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Prevent flicker & hide scrollbar -->
    <style>
        /* Hide scrollbar globally but allow scroll */
        ::-webkit-scrollbar {
            width: 0px;
            height: 0px;
        }

        html, body {
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE */
            overflow-x: hidden;
        }

        /* Prevent Alpine flicker */
        [x-cloak] {
            display: none !important;
        }

        /* Prevent full page flicker */
        body {
            opacity: 0;
            transition: opacity 0.2s ease-in-out;
        }

        body.loaded {
            opacity: 1;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100 h-screen overflow-hidden">
    <div class="h-screen flex">

        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-blue-900 via-sky-800 to-sky-600 text-white flex flex-col">
            <div class="py-3 flex justify-center border-b border-sky-500">
                <img src="{{ asset('images/logo6SM.png') }}" alt="Logo Kominfo" class="w-24 md:w-28 h-auto object-contain">
            </div>
            <div class="p-4 text-xl font-bold border-b border-sky-500 text-center">
                Simanis Dimas
            </div>

            <nav class="p-4 space-y-2 text-sm flex-1 overflow-y-auto no-scrollbar">
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded hover:bg-sky-700 transition">
                    Dashboard
                </a>
                
                {{-- Barang Dropdown --}}
                <div x-data="{ open: localStorage.getItem('dropdown-barang') === 'true' }" x-init="open = localStorage.getItem('dropdown-barang') === 'true'" class="space-y-1">
                    <button @click="open = !open; localStorage.setItem('dropdown-barang', open)"
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
                        <a href="{{ route('barang.history') }}" class="block px-3 py-1 hover:underline">Riwayat</a>
                    </div>
                </div>

                {{-- Device Dropdown --}}
                <div x-data="{ open: localStorage.getItem('dropdown-device') === 'true' }" x-init="open = localStorage.getItem('dropdown-device') === 'true'" class="space-y-1">
                    <button @click="open = !open; localStorage.setItem('dropdown-device', open)"
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
                            <a href="{{ route('device.riwayatAll') }}" class="block px-3 py-1 hover:underline">Riwayat Device</a>
                        @else
                            <span class="block px-3 py-1 text-gray-400">Riwayat Device (belum ada device)</span>
                        @endif
                    </div>
                </div>

                {{-- Kendaraan Dropdown --}}
                <div x-data="{ open: localStorage.getItem('dropdown-kendaraan') === 'true' }" x-init="open = localStorage.getItem('dropdown-kendaraan') === 'true'" class="space-y-1">
                    <button @click="open = !open; localStorage.setItem('dropdown-kendaraan', open)"
                            class="w-full text-left px-3 py-2 rounded hover:bg-sky-700 flex justify-between items-center transition">
                        Kendaraan
                        <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transform transition-transform"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="ml-4 space-y-1 text-white">
                        <a href="{{ route('kendaraan.index') }}" class="block px-3 py-1 hover:underline">Manajemen Kendaraan</a>
                        @php
                            $userKendaraan = \App\Models\Kendaraan::where('user_id', auth()->id())->first();
                        @endphp
                        @if ($userKendaraan)
                            <a href="{{ route('kendaraan.riwayat') }}" class="block px-3 py-1 hover:underline">Riwayat Kendaraan</a>
                        @else
                            <span class="block px-3 py-1 text-gray-400">Riwayat Kendaraan (belum ada Kendaraan)</span>
                        @endif
                    </div>
                </div>
            </nav>

            <!-- Admin Section -->
            <div x-data="{ open: localStorage.getItem('dropdown-user') === 'true' }" x-init="open = localStorage.getItem('dropdown-user') === 'true'" class="px-4 mb-6">
                <hr class="border-t border-sky-500 mb-4">
                <button @click="open = !open; localStorage.setItem('dropdown-user', open)"
                        class="w-full text-left flex justify-between items-center px-3 py-2 rounded hover:bg-sky-700 transition">
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
                        <button type="submit" class="hover:underline">Logout</button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            @isset($header)
                <header class="bg-white shadow shrink-0">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="flex-1 overflow-y-auto no-scrollbar py-6 px-4 sm:px-6 lg:px-8 bg-gray-100">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Load body only when ready -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            document.body.classList.add('loaded');
        });
    </script>
</body>
</html>
