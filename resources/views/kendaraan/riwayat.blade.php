<x-app-layout>
    <x-slot name="title">Riwayat Perawatan Kendaraan</x-slot>

    <div class="max-w-7xl mx-auto py-10 px-6">
        {{-- Header --}}
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-[#003973] via-[#2980B9] to-[#6DD5FA] drop-shadow">
                Riwayat Perawatan
            </h2>
            <p class="text-slate-500 mt-1 text-base">
                Daftar laporan perawatan untuk semua Kendaraan.
            </p>
        </div>

        {{-- Filter Dropdown --}}
        <form method="GET" action="{{ route('kendaraan.riwayatAll') }}" class="mb-6">
            <label for="kendaraan_id" class="block mb-2 text-sm font-medium text-slate-700">Filter Berdasarkan Kendaraan</label>
            <select name="kendaraan_id" id="kendaraan_id" onchange="this.form.submit()"
                class="w-full md:w-1/3 border border-slate-300 rounded-md shadow-sm px-4 py-2 text-sm text-slate-700 focus:ring focus:ring-sky-200 focus:border-sky-400">
                <option value="">Semua Kendaraan</option>
                @foreach ($kendaraans as $kendaraan)
                    <option value="{{ $kendaraan->id }}" {{ request('kendaraan_id') == $kendaraan->id ? 'selected' : '' }}>
                        {{ $kendaraan->nama }}
                    </option>
                @endforeach
            </select>
        </form>

        {{-- Tabel Riwayat --}}
        <div class="bg-white border border-slate-200 shadow-xl rounded-2xl overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-gradient-to-r from-sky-700 to-teal-600 text-white uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-3 text-left">Tanggal</th>
                        <th class="px-6 py-3 text-left">Kendaraan</th>
                        <th class="px-6 py-3 text-left">Kategori</th>
                        <th class="px-6 py-3 text-left">Deskripsi</th>
                        <th class="px-6 py-3 text-left">Lampiran</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse ($riwayat as $index => $item)
                        <tr class="hover:bg-sky-50 transition duration-150">
                            <td class="px-6 py-4 text-slate-700">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $item->kendaraan->nama ?? '-' }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-teal-700">
                                {{ $item->kategori_perawatan }}
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $item->deskripsi }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($item->bukti)
                                    <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank" class="text-sky-600 hover:underline">Lihat</a>
                                @else
                                    <span class="text-slate-400 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <button onclick="openModal({{ $index }})"
                                    class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-6 py-2 rounded-md shadow-md hover:opacity-90 transition text-sm">
                                    Lihat Detail
                                </button>
                            </td>
                        </tr>

                        {{-- Modal Detail --}}
                        <div id="modal-{{ $index }}" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
                            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl p-6 relative animate-fade-in-down overflow-y-auto max-h-screen">
                                <button onclick="closeModal({{ $index }})"
                                    class="absolute top-3 right-4 text-slate-500 hover:text-slate-800 text-xl font-bold">&times;</button>
                                
                                <h3 class="text-2xl font-bold text-slate-800 mb-4">Detail Perawatan Kendaraan</h3>
                                <div class="space-y-3 text-sm text-slate-700">
                                    <p><strong>Nama Kendaraan:</strong> {{ $item->kendaraan->nama ?? '-' }}</p>
                                    <p><strong>ID Kendaraan:</strong> {{ $item->kendaraan->id ?? '-' }}</p>
                                    <p><strong>Nama User:</strong> {{ $item->kendaraan->user->name ?? '-' }}</p>
                                    <p><strong>Spesifikasi:</strong> {{ $item->kendaraan->spesifikasi ?? '-' }}</p>
                                    <p><strong>Tanggal Perawatan:</strong> {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</p>
                                    <p><strong>Kategori:</strong> {{ $item->kategori_perawatan }}</p>
                                    <p><strong>Deskripsi:</strong> {{ $item->deskripsi }}</p>
                                    <p>
                                        <strong>Lampiran:</strong><br>
                                        @if ($item->bukti)
                                            @if (Str::endsWith($item->bukti, ['.jpg', '.jpeg', '.png']))
                                                <img src="{{ asset('storage/' . $item->bukti) }}" alt="lampiran"
                                                    class="w-full rounded-lg border shadow mt-2">
                                            @else
                                                <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank" class="text-sky-600 underline">Lihat File</a>
                                            @endif
                                        @else
                                            <span class="text-slate-400 italic">Tidak ada lampiran</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-slate-400 italic">Belum ada laporan perawatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal JS --}}
    <script>
        function openModal(index) {
            document.getElementById(`modal-${index}`).classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal(index) {
            document.getElementById(`modal-${index}`).classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    </script>

    {{-- Animasi --}}
    <style>
        @keyframes fade-in-down {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in-down {
            animation: fade-in-down 0.25s ease-out;
        }
    </style>
</x-app-layout>
