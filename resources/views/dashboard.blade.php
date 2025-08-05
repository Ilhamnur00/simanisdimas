<x-app-layout>
    <x-slot name="header">
        <div class="mb-6">
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-sky-900 via-indigo-900 to-slate-800">
                Dashboard Pengguna
            </h2>
            <p class="text-sm text-slate-600 mt-1">
                Halo <span class="font-semibold">{{ Auth::user()->name }}</span>, selamat datang di Sistem Informasi Manajemen Inventaris.
            </p>
        </div>
    </x-slot>

    <div class="bg-gradient-to-b from-slate-100 via-white to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">

            {{-- Statistik Ringkasan --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Total Device -->
                <div class="flex items-center p-6 bg-white rounded-xl shadow-md border-l-4 border-sky-600 hover:shadow-lg transition">
                    <div class="p-3 bg-sky-100 text-sky-700 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9.75 17L14.25 7M19 17H5" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-600">Total Device Saya</h4>
                        <div class="text-3xl font-bold text-sky-700 mt-1">
                            {{ \App\Models\Device::where('user_id', auth()->id())->count() }}
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Perangkat tercatat atas nama Anda</p>
                    </div>
                </div>

                <!-- Total Kendaraan -->
                <div class="flex items-center p-6 bg-white rounded-xl shadow-md border-l-4 border-indigo-600 hover:shadow-lg transition">
                    <div class="p-3 bg-indigo-100 text-indigo-700 rounded-full mr-4">
                        <!-- Ikon mobil -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 13l1-3a1 1 0 011-1h14a1 1 0 011 1l1 3M5 13h14M6 16h.01M18 16h.01M6 16a2 2 0 104 0 2 2 0 00-4 0zM14 16a2 2 0 104 0 2 2 0 00-4 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-600">Total Kendaraan yang Dimiliki</h4>
                        <div class="text-3xl font-bold text-indigo-700 mt-1">
                            {{ \App\Models\Kendaraan::where('user_id', auth()->id())->count() }}
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Kendaraan tercatat atas nama Anda</p>
                    </div>
                </div>
            </div>

            {{-- Informasi Sistem --}}
            <div class="rounded-2xl p-6 bg-gradient-to-r from-sky-700 via-sky-600 to-sky-500 text-white shadow-lg">
                <h3 class="text-lg font-bold mb-3">📢 Informasi Sistem</h3>
                <ul class="list-disc pl-6 text-sm text-sky-100 space-y-1 leading-relaxed">
                    <li>Gunakan sistem ini untuk mencatat dan melacak status perangkat Anda.</li>
                    <li>Selalu perbarui data perangkat dan riwayat perawatannya.</li>
                    <li>Ajukan laporan jika perangkat mengalami kerusakan.</li>
                    <li>Periksa kembali data Anda secara berkala untuk menjaga keakuratan.</li>
                    <li>Hubungi admin jika terdapat kendala dalam penggunaan sistem.</li>
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>
