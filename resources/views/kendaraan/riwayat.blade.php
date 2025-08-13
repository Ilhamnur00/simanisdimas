<x-app-layout>
    <x-slot name="title">Riwayat Kendaraan</x-slot>

    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-[#003973] via-[#2980B9] to-[#6DD5FA] drop-shadow">
                Riwayat Kendaraan Dinas
            </h2>
            <p class="text-slate-700 text-base">Sistem Manajemen Inventaris Dinas Komunikasi dan Informatika Kabupaten Banyumas</p>
            <p class="text-sm italic text-slate-500">“Daftar Laporan Perawatan & Pajak Kendaraan Dinas”</p>
        </div>
    </x-slot>

    <div class="bg-white shadow-xl rounded-xl p-6">
        {{-- Filter Dropdown --}}
        <div class="mb-6">
            <label for="filter" class="block mb-2 text-sm font-medium text-slate-700">Pilih Jenis Riwayat</label>
            <select id="filter" onchange="filterTable(this.value)"
                class="w-full md:w-1/3 border border-slate-300 rounded-md shadow-sm px-4 py-2 text-sm text-slate-700 focus:ring focus:ring-sky-200 focus:border-sky-400">
                <option value="perawatan">Riwayat Perawatan Kendaraan</option>
                <option value="pajak">Riwayat Laporan Pajak</option>
            </select>
        </div>

        {{-- Tabel Perawatan --}}
        <div id="tabelPerawatan" class="bg-white border border-slate-200 shadow-xl rounded-2xl overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-gradient-to-r from-sky-700 to-sky-500 text-white uppercase text-xs font-semibold text-left">
                    <tr>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Kendaraan</th>
                        <th class="px-6 py-3">No Polisi</th>
                        <th class="px-6 py-3">Jenis Perawatan</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse ($riwayatPerawatan as $data)
                        <tr class="hover:bg-sky-50 transition duration-150">
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y') }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $data->kendaraan->nama }}</td>
                            <td class="px-6 py-4">{{ $data->kendaraan->no_polisi ?? '-' }}</td>
                            <td class="px-6 py-4 font-semibold text-teal-700">{{ $data->kategori_perawatan }}</td>

                            <td class="px-6 py-4">
                                <button onclick="openModal({{ $loop->index }})"
                                    class="bg-gradient-to-r from-sky-700 to-sky-500 text-white px-6 py-2 rounded-md shadow-md hover:from-sky-800 hover:to-sky-600 hover:opacity-90 transition text-sm">
                                    Lihat Detail
                                </button>
                            </td>
                        </tr>

                        {{-- Modal Perawatan --}}
                        <div id="modal-{{ $loop->index }}" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
                            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl p-6 relative animate-fade-in-down overflow-y-auto max-h-screen">
                                <button onclick="closeModal({{ $loop->index }})"
                                    class="absolute top-3 right-4 text-slate-500 hover:text-slate-800 text-xl font-bold">&times;</button>

                                <h3 class="text-2xl font-bold mb-4">Detail Riwayat Perawatan</h3>
                                <div class="space-y-3 text-sm text-slate-700">
                                    <p><strong>Nama Kendaraan:</strong> {{ $data->kendaraan->nama ?? '-' }}</p>
                                    <p><strong>ID Kendaraan:</strong> {{ $data->kendaraan->id ?? '-' }}</p>
                                    <p><strong>Nomor Polisi:</strong> {{ $data->kendaraan->no_polisi ?? '-' }}</p>
                                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y') }}</p>
                                    <p><strong>Kategori:</strong> {{ $data->kategori_perawatan }}</p>
                                    <p><strong>Deskripsi:</strong> {{ $data->deskripsi }}</p>
                                    <p><strong>Lampiran:</strong></p>
                                    @php $lampiran = $data->bukti ?? $data->lampiran; @endphp
                                    @if ($lampiran)
                                        @if (Str::endsWith($lampiran, ['.jpg', '.jpeg', '.png']))
                                            <img src="{{ asset('storage/' . $lampiran) }}" alt="lampiran" class="w-full rounded-lg border shadow mt-2">
                                        @else
                                            <a href="{{ asset('storage/' . $lampiran) }}" target="_blank" class="text-sky-600 underline">Lihat File</a>
                                        @endif
                                    @else
                                        <span class="text-slate-400 italic">Tidak ada lampiran</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr><td colspan="5" class="text-center py-6 text-slate-400 italic">Belum ada laporan perawatan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tabel Pajak --}}
        <div id="tabelPajak" class="bg-white border border-slate-200 shadow-xl rounded-2xl overflow-x-auto hidden">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-gradient-to-r from-sky-700 to-sky-500 text-white uppercase text-xs font-semibold text-left">
                    <tr>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Kendaraan</th>
                        <th class="px-6 py-3">No Polisi</th>
                        <th class="px-6 py-3">Jenis Pajak</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse ($riwayatPajak as $data)
                        <tr class="hover:bg-sky-50 transition duration-150">
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y') }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $data->kendaraan->nama }}</td>
                            <td class="px-6 py-4">{{ $data->kendaraan->no_polisi ?? '-' }}</td>
                            <td class="px-6 py-4 font-semibold text-yellow-700">{{ $data->jenis_pajak ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <button onclick="openModalPajak({{ $loop->index }})"
                                    class="bg-gradient-to-r from-sky-700 to-sky-500 text-white px-6 py-2 rounded-md shadow-md hover:from-sky-800 hover:to-sky-600 hover:opacity-90 transition text-sm">
                                    Lihat Detail
                                </button>
                            </td>
                        </tr>

                        {{-- Modal Pajak --}}
                        <div id="modal-pajak-{{ $loop->index }}" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
                            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl p-6 relative animate-fade-in-down overflow-y-auto max-h-screen">
                                <button onclick="closeModalPajak({{ $loop->index }})"
                                    class="absolute top-3 right-4 text-slate-500 hover:text-slate-800 text-xl font-bold">&times;</button>

                                <h3 class="text-2xl font-bold mb-4">Detail Laporan Pajak</h3>
                                <div class="space-y-3 text-sm text-slate-700">
                                    <p><strong>Nama Kendaraan:</strong> {{ $data->kendaraan->nama ?? '-' }}</p>
                                    <p><strong>ID Kendaraan:</strong> {{ $data->kendaraan->id ?? '-' }}</p>
                                    <p><strong>Nomor Polisi:</strong> {{ $data->kendaraan->no_polisi ?? '-' }}</p>
                                    <p><strong>Tanggal Pajak:</strong> {{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y') }}</p>
                                    <p><strong>Jenis Pajak:</strong> {{ $data->jenis_pajak }}</p>
                                    <p><strong>Deskripsi:</strong> {{ $data->deskripsi ?? '-' }}</p>
                                    <p><strong>Lampiran:</strong></p>
                                    @if ($data->bukti)
                                        @if (Str::endsWith($data->bukti, ['.jpg', '.jpeg', '.png']))
                                            <img src="{{ asset('storage/' . $data->bukti) }}" alt="lampiran" class="w-full rounded-lg border shadow mt-2">
                                        @else
                                            <a href="{{ asset('storage/' . $data->bukti) }}" target="_blank" class="text-sky-600 underline">Lihat File</a>
                                        @endif
                                    @else
                                        <span class="text-slate-400 italic">Tidak ada lampiran</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr><td colspan="5" class="text-center py-6 text-slate-400 italic">Belum ada laporan pajak.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- JS Filter & Modal --}}
    <script>
        function filterTable(value) {
            document.getElementById('tabelPerawatan').classList.add('hidden');
            document.getElementById('tabelPajak').classList.add('hidden');
            if (value === 'perawatan') {
                document.getElementById('tabelPerawatan').classList.remove('hidden');
            } else {
                document.getElementById('tabelPajak').classList.remove('hidden');
            }
        }

        function openModal(index) {
            document.getElementById(`modal-${index}`).classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        function closeModal(index) {
            document.getElementById(`modal-${index}`).classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        function openModalPajak(index) {
            document.getElementById(`modal-pajak-${index}`).classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        function closeModalPajak(index) {
            document.getElementById(`modal-pajak-${index}`).classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    </script>
</x-app-layout>
