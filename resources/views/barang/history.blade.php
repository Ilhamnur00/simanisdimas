<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📜 Riwayat Permintaan Barang
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="w-full table-auto border-collapse">
                    <thead class="bg-gray-100 text-left text-gray-700">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Nama Barang</th>
                            <th class="px-4 py-3 text-center">Jumlah</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        @forelse ($transaksi as $index => $item)
                            <tr class="border-t">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                <td class="px-4 py-3">{{ $item->barang->kategori->nama_kategori ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $item->barang->nama_barang }}</td>
                                <td class="px-4 py-3 text-center">{{ $item->jumlah_barang }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded
                                        {{ $item->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                            ($item->status === 'disetujui' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button onclick="showDetail({ $index })"
                                        class="text-blue-600 hover:text-blue-800 font-medium text-sm underline">
                                        Lihat Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada permintaan barang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL --}}
    <div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white w-full max-w-md mx-auto rounded shadow-lg p-6 relative">
            <h3 class="text-lg font-bold mb-4">📦 Detail Permintaan Barang</h3>

            <div class="space-y-2 text-sm text-gray-700">
                <p><strong>Tanggal:</strong> <span id="detailTanggal"></span></p>
                <p><strong>Kategori:</strong> <span id="detailKategori"></span></p>
                <p><strong>Barang:</strong> <span id="detailBarang"></span></p>
                <p><strong>Jumlah:</strong> <span id="detailJumlah"></span></p>
                <p><strong>Status:</strong> <span id="detailStatus"></span></p>
            </div>

            <div class="text-right mt-4">
                <button onclick="closeModal()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- JSON Data untuk Script --}}
    <script id="transaksi-data" type="application/json">
        {!! $transaksi->toJson() !!}
    </script>

    {{-- Script untuk Popup --}}
    <script>
        const transaksi = JSON.parse(document.getElementById('transaksi-data').textContent);

        function showDetail(index) {
            const data = transaksi[index];
            if (!data) return alert("Data tidak ditemukan");

            document.getElementById("detailTanggal").textContent = formatTanggal(data.tanggal);
            document.getElementById("detailKategori").textContent = data.barang?.kategori?.nama_kategori ?? "-";
            document.getElementById("detailBarang").textContent = data.barang?.nama_barang ?? "-";
            document.getElementById("detailJumlah").textContent = data.jumlah_barang;
            document.getElementById("detailStatus").textContent = capitalize(data.status);

            document.getElementById("detailModal").classList.remove("hidden");
        }

        function closeModal() {
            document.getElementById("detailModal").classList.add("hidden");
        }

        function formatTanggal(tanggal) {
            const options = { day: '2-digit', month: 'short', year: 'numeric' };
            return new Date(tanggal).toLocaleDateString('id-ID', options);
        }

        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    </script>
</x-app-layout>
