<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-[#003973] via-[#2980B9] to-[#6DD5FA] drop-shadow">
                Inventaris Barang
            </h2>
            <p class="text-slate-700 text-base">Aplikasi Inventaris Dinas Komunikasi dan Informatika</p>
            <p class="text-sm italic text-slate-500">“Daftar Barang Inventaris yang Tersedia”</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow-xl rounded-xl p-6">

            {{-- Filter Bar --}}
            <div class="flex flex-wrap md:flex-nowrap gap-4 mb-6 items-end">
                {{-- Input Search --}}
                <div class="flex-1 min-w-[200px]">
                    <label for="searchInput" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                    <input type="text" id="searchInput" name="search"
                        placeholder="Contoh: Proyektor, Meja..."
                        value="{{ request('search') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 sm:text-sm" />
                </div>

                {{-- Dropdown Kategori --}}
                <div class="flex-1 min-w-[180px]">
                    <label for="kategoriSelect" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select id="kategoriSelect" name="kategori"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 sm:text-sm">
                        <option value="">Semua Kategori</option>
                        @foreach(\App\Models\Kategori::all() as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol Cari --}}
                <div class="flex-none">
                    <label class="block text-sm font-medium text-transparent">Cari</label>
                    <button onclick="applyFilters()"
                        class="bg-gradient-to-r from-sky-700 to-sky-500 text-white px-6 py-2 rounded-md shadow-md hover:from-sky-800 hover:to-sky-600 hover:opacity-90 transition text-sm">
                        Cari
                    </button>
                </div>

                {{-- Tombol Request Barang --}}
                <div class="flex-none">
                    <label class="block text-sm font-medium text-transparent">Request</label>
                    <a href="{{ route('barang.request') }}"
                        class="inline-block bg-gradient-to-r from-sky-700 to-sky-500 text-white px-6 py-2 rounded-md shadow-md hover:from-sky-800 hover:to-sky-600 hover:opacity-90 transition text-sm">
                        Request Barang
                    </a>
                </div>
            </div>

            {{-- Tabel Data --}}
            <div class="bg-white border border-slate-200 shadow-xl rounded-2xl overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-gradient-to-r from-sky-700 to-sky-500 text-white uppercase text-xs font-semibold text-left">
                        <tr>
                            <th class="px-6 py-3">Kode</th>
                            <th class="px-6 py-3">Nama Barang</th>
                            <th class="px-6 py-3">Kategori</th>
                            <th class="px-6 py-3 text-center">Stok</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($barang as $item)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-3 whitespace-nowrap">{{ $item->kode_barang }}</td>
                                <td class="px-6 py-3 whitespace-nowrap">{{ $item->nama_barang }}</td>
                                <td class="px-6 py-3 whitespace-nowrap">{{ $item->kategori->nama_kategori }}</td>
                                <td class="px-6 py-3 text-center font-semibold">
                                    {{ $item->stok }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada barang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- Script Filter --}}
    <script>
        const searchInput = document.getElementById('searchInput');
        const kategoriSelect = document.getElementById('kategoriSelect');

        // Jalankan filter saat kategori diubah
        kategoriSelect.addEventListener('change', applyFilters);

        function applyFilters() {
            const params = new URLSearchParams(window.location.search);

            const search = searchInput.value.trim();
            const kategori = kategoriSelect.value;

            if (search) {
                params.set('search', search);
            } else {
                params.delete('search');
            }

            if (kategori) {
                params.set('kategori', kategori);
            } else {
                params.delete('kategori');
            }

            window.location.search = params.toString();
        }
    </script>
</x-app-layout>
