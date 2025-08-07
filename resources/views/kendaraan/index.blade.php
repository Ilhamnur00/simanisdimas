<x-app-layout>
    <x-slot name="title">
        Perawatan Kendaraan
    </x-slot>

    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-[#003973] via-[#2980B9] to-[#6DD5FA] drop-shadow">
                Perawatan Kendaraan
            </h2>
            <p class="text-slate-700 text-base">Aplikasi Inventaris Dinas Komunikasi dan Informatika</p>
            <p class="text-sm italic text-slate-500">“Laporan Perawatan Kendaraan oleh Pengguna”</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="bg-white shadow-xl rounded-xl p-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                @if ($kendaraans->count())
                    <div class="flex justify-end mb-6 gap-3">
                        <a href="/kendaraan/laporan-perawatan"
                        class="bg-gradient-to-r from-sky-700 to-sky-500 text-white px-4 py-2 rounded-lg shadow hover:from-sky-800 hover:to-sky-600 text-sm font-semibold transition">
                            Laporkan Perawatan
                        </a>
                        <a href="{{ route('kendaraan.lapor-pajak') }}"
                        class="bg-gradient-to-r from-sky-700 to-sky-500 text-white px-4 py-2 rounded-lg shadow hover:from-sky-800 hover:to-sky-600 text-sm font-semibold transition">
                            Laporkan Pajak
                        </a>
                    </div>
                @endif

                <div class="bg-white border border-slate-200 shadow-xl rounded-2xl overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm text-left">
                        <thead class="bg-gradient-to-r from-sky-700 to-sky-500 text-white uppercase text-xs font-semibold text-left">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Nama Kendaraan</th>
                                <th class="px-6 py-4">No. Polisi</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4">Spesifikasi</th>
                                <th class="px-6 py-4">Serah Terima</th>
                                <th class="px-6 py-4">Tanggal Pajak</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800 divide-y divide-slate-100">
                            @forelse ($kendaraans as $kendaraan)
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $tanggalPajak = $kendaraan->tanggal_pajak ? \Carbon\Carbon::parse($kendaraan->tanggal_pajak) : null;
                                    $diff = $tanggalPajak ? $now->diffInDays($tanggalPajak, false) : null;

                                    $textClass = 'text-slate-600';
                                    if ($diff !== null) {
                                        if ($diff < 0) {
                                            $textClass = 'text-red-600 font-semibold';
                                        } elseif ($diff <= 30) {
                                            $textClass = 'text-yellow-600 font-semibold';
                                        } else {
                                            $textClass = 'text-green-600 font-semibold';
                                        }
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $kendaraan->id }}</td>
                                    <td class="px-6 py-4">{{ $kendaraan->nama }}</td>
                                    <td class="px-6 py-4">{{ $kendaraan->no_polisi }}</td>
                                    <td class="px-6 py-4">{{ $kendaraan->kategori ?? '-' }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $kendaraan->spesifikasi ?? '-' }}</td>
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $kendaraan->tanggal_serah_terima ? \Carbon\Carbon::parse($kendaraan->tanggal_serah_terima)->translatedFormat('d-m-Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 {{ $textClass }}">
                                        {{ $tanggalPajak ? $tanggalPajak->translatedFormat('d-m-Y') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-6 text-center text-slate-500 italic">
                                        Tidak ada data kendaraan yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
