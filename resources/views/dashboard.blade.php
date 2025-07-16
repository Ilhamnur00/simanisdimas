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

    <div class="py-12 bg-gradient-to-b from-slate-100 via-white to-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

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

                <!-- Riwayat Perawatan -->
                <div class="flex items-center p-6 bg-white rounded-xl shadow-md border-l-4 border-indigo-600 hover:shadow-lg transition">
                    <div class="p-3 bg-indigo-100 text-indigo-700 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 4h10M5 11h14M7 15h10M9 19h6" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-600">Riwayat Perawatan</h4>
                        <div class="text-3xl font-bold text-indigo-700 mt-1">
                            {{ \App\Models\Maintenance::whereHas('device', function ($q) {
                                $q->where('user_id', auth()->id());
                            })->count() }}
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Laporan perawatan yang telah diajukan</p>
                    </div>
                </div>
            </div>

            {{-- Informasi Sistem --}}
            <div class="rounded-2xl p-6 bg-gradient-to-r from-sky-900 via-indigo-900 to-slate-900 text-white shadow-lg">
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
