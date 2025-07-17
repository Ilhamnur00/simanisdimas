<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-[#003973] via-[#2980B9] to-[#6DD5FA] drop-shadow">
            Riwayat Permintaan Barang
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 shadow">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white border border-slate-200 shadow-xl rounded-2xl overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-gradient-to-r from-sky-700 to-teal-600 text-white uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-5 py-4">#</th>
                            <th class="px-5 py-4">Tanggal</th>
                            <th class="px-5 py-4">Kategori</th>
                            <th class="px-5 py-4">Nama Barang</th>
                            <th class="px-5 py-4 text-center">Jumlah</th>
                            <th class="px-5 py-4 text-center">Status</th>
                            <th class="px-5 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        @forelse ($transaksi as $index => $item)
                            <tr class="border-t hover:bg-gray-50 hover:shadow-sm transition-all duration-200 ease-in-out">
                                <td class="px-5 py-4">{{ $index + 1 }}</td>
                                <td class="px-5 py-4">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                <td class="px-5 py-4">{{ $item->barang->kategori->nama_kategori ?? '-' }}</td>
                                <td class="px-5 py-4">{{ $item->barang->nama_barang }}</td>
                                <td class="px-5 py-4 text-center">{{ $item->jumlah_barang }}</td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-full transition
                                        {{ $item->status === 'pending' ? 'bg-yellow-200 text-yellow-800' :
                                            ($item->status === 'disetujui' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800') }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <button onclick="showDetail({{ $index }})"
                                        class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm underline transition duration-150">
                                        Lihat Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-6 text-center text-gray-500">Belum ada permintaan barang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL --}}
    <div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 backdrop-blur-sm hidden transition-opacity duration-300 ease-in-out">
        <div class="bg-white/90 backdrop-blur-xl w-full max-w-md mx-auto rounded-2xl shadow-2xl p-6 border border-gray-200 animate-fade-in">
            <h3 class="text-xl font-bold mb-4 text-slate-800">📦 Detail Permintaan Barang</h3>

            <div class="space-y-2 text-sm text-gray-700">
                <p><strong>Tanggal:</strong> <span id="detailTanggal"></span></p>
                <p><strong>Kategori:</strong> <span id="detailKategori"></span></p>
                <p><strong>Barang:</strong> <span id="detailBarang"></span></p>
                <p><strong>Jumlah:</strong> <span id="detailJumlah"></span></p>
                <p><strong>Status:</strong> <span id="detailStatus"></span></p>
            </div>

            <div class="text-right mt-5">
                <button onclick="closeModal()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- JSON Data --}}
    <script id="transaksi-data" type="application/json">
        {!! $transaksi->toJson() !!}
    </script>

    {{-- Script --}}
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

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out forwards;
        }
    </style>
</x-app-layout>
