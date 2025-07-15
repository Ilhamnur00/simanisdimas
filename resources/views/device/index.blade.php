<x-app-layout>
    <x-slot name="title">
        Manajemen Device
    </x-slot>

    <section class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <!-- Header + Tombol -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="border-l-4 border-sky-600 pl-4">
                <h1 class="text-4xl font-extrabold text-sky-800 tracking-tight">Manajemen Perangkat Elektronik</h1>
                <p class="mt-1 text-slate-600 text-base">Aplikasi Inventaris Dinas Komunikasi dan Informatika</p>
                <p class="text-sm italic text-slate-500">“Laporan Perawatan Perangkat oleh Pengguna”</p>
            </div>

            @php
                $myDevice = $devices->firstWhere('user_id', auth()->id());
            @endphp

            @if ($myDevice)
                <a href="{{ route('devices.show', $myDevice->id) }}"
                   class="inline-flex items-center px-5 py-2 bg-sky-700 hover:bg-sky-800 text-white text-sm font-medium rounded-lg shadow-md transition">
                    + Laporkan Perawatan
                </a>
            @endif
        </div>

        <!-- Tabel -->
        <div class="overflow-x-auto bg-white border border-slate-200 shadow-lg rounded-2xl">
            <table class="min-w-full divide-y divide-slate-200 text-sm text-slate-700">
                <thead class="bg-sky-700 text-white uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold tracking-wide">ID Device</th>
                        <th class="px-6 py-3 text-left font-semibold">Nama User</th>
                        <th class="px-6 py-3 text-left font-semibold">Nama Device</th>
                        <th class="px-6 py-3 text-left font-semibold">Spesifikasi</th>
                        <th class="px-6 py-3 text-left font-semibold">Tanggal Serah Terima</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse ($devices as $device)
                        <tr class="hover:bg-sky-50 transition duration-150">
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $device->id }}</td>
                            <td class="px-6 py-4">{{ $device->user->name ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $device->nama }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $device->spesifikasi }}</td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $device->tanggal_serah_terima ? \Carbon\Carbon::parse($device->tanggal_serah_terima)->translatedFormat('d F Y') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-slate-500 italic">
                                Tidak ada data perangkat yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-app-layout>
