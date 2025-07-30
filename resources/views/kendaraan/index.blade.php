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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($kendaraans->count())
                <!-- Dropdown + Tombol -->
                <div class="flex justify-end mb-6 items-center gap-2">
                    <div class="flex gap-2">
                        <select id="kendaraanSelect"
                            class="border border-gray-300 rounded-md px-4 py-2 text-sm shadow-sm focus:ring focus:ring-indigo-200">
                            <option value="">-- Pilih Kendaraan --</option>
                            @foreach ($kendaraans as $kendaraan)
                                <option value="{{ route('perawatan.create', $kendaraan->id) }}">
                                    {{ $kendaraan->nama }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" onclick="redirectToKendaraan()"
                            class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-4 py-2 rounded-lg shadow hover:opacity-90 text-sm font-semibold">
                            Laporkan Perawatan
                        </button>
                    </div>
                </div>

                <script>
                    function redirectToKendaraan() {
                        const select = document.getElementById('kendaraanSelect');
                        const url = select.value;
                        if (url) {
                            window.location.href = url;
                        } else {
                            alert('Silakan pilih kendaraan terlebih dahulu.');
                        }
                    }
                </script>
            @endif

            <!-- Tabel Kendaraan -->
            <div class="bg-white border border-slate-200 shadow-xl rounded-2xl overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm text-left">
                    <thead class="bg-gradient-to-r from-sky-700 to-teal-600 text-white text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Nama User</th>
                            <th class="px-6 py-4">Nama Kendaraan</th>
                            <th class="px-6 py-4">No. Polisi</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Spesifikasi</th>
                            <th class="px-6 py-4">Tanggal Serah Terima</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 divide-y divide-slate-100">
                        @forelse ($kendaraans as $kendaraan)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-semibold text-slate-800">{{ $kendaraan->id }}</td>
                                <td class="px-6 py-4">{{ $kendaraan->user->name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $kendaraan->nama }}</td>
                                <td class="px-6 py-4">{{ $kendaraan->no_polisi }}</td>
                                <td class="px-6 py-4">{{ $kendaraan->kategori ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $kendaraan->spesifikasi ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $kendaraan->tanggal_serah_terima ? \Carbon\Carbon::parse($kendaraan->tanggal_serah_terima)->translatedFormat('d F Y') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-6 text-center text-slate-500 italic">
                                    Tidak ada data kendaraan yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
