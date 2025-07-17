<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-sky-900 via-indigo-900 to-slate-800">
                Daftar Stok Barang
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow-xl rounded-xl p-6">

            {{-- Filter bar: sejajar --}}
            <div class="flex flex-wrap md:flex-nowrap gap-4 mb-6 items-end">
                {{-- Input Search --}}
                <div class="flex-1 min-w-[200px]">
                    <label for="searchInput" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                    <input type="text" id="searchInput" placeholder="Contoh: Proyektor, Meja..."
                        value="{{ request('search') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 sm:text-sm" />
                </div>

                {{-- Dropdown Kategori --}}
                <div class="flex-1 min-w-[180px]">
                    <label for="kategoriSelect" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select id="kategoriSelect"
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
                        class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-6 py-2 rounded-md shadow-md hover:opacity-90 transition text-sm">
                        Cari
                    </button>
                </div>

                {{-- Tombol Request Barang --}}
                <div class="flex-none">
                    <label class="block text-sm font-medium text-transparent">Request</label>
                    <a href="{{ route('barang.request') }}"
                        class="inline-block bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-6 py-2 rounded-md shadow-md hover:opacity-90 transition text-sm">
                        Request Barang
                    </a>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="bg-white border border-slate-200 shadow-xl rounded-2xl overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-gradient-to-r from-sky-700 to-teal-600 text-white uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-4 py-3 w-1/6 font-semibold">Kode</th>
                            <th class="px-4 py-3 w-1/3 font-semibold">Nama Barang</th>
                            <th class="px-4 py-3 w-1/4 font-semibold">Kategori</th>
                            <th class="px-4 py-3 w-1/6 font-semibold">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barang as $item)
                            <tr class="border-b hover:bg-slate-50 transition">
                                <td class="px-4 py-2">{{ $item->kode_barang }}</td>
                                <td class="px-4 py-2">{{ $item->nama_barang }}</td>
                                <td class="px-4 py-2">{{ $item->kategori->nama_kategori }}</td>
                                <td class="px-4 py-2">{{ $item->stok }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-gray-500">
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

        kategoriSelect.addEventListener('change', applyFilters);

        function applyFilters() {
            const search = encodeURIComponent(searchInput.value);
            const kategori = encodeURIComponent(kategoriSelect.value);

            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (kategori) params.append('kategori', kategori);

            window.location.href = `?${params.toString()}`;
        }
    </script>
</x-app-layout>
