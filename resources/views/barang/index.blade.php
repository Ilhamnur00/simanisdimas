<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📦 Daftar Stok Barang
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

            {{-- Filter Tanpa Form --}}
            <div class="mb-6 flex flex-col md:flex-row flex-wrap gap-4 items-stretch md:items-center">
                {{-- Input Search --}}
                <input type="text" id="searchInput" placeholder="Cari nama barang..."
                    value="{{ request('search') }}"
                    class="border px-4 py-2 rounded w-full md:w-1/2" />

                {{-- Dropdown Kategori --}}
                <select id="kategoriSelect" class="border px-4 py-2 rounded w-full md:w-1/4">
                    <option value="">Semua Kategori</option>
                    @foreach(\App\Models\Kategori::all() as $kategori)
                        <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>

                {{-- Tombol Cari --}}
                <button onclick="applyFilters()"
                    class="bg-blue-500 text-white px-6 py-2 rounded text-sm w-full md:w-20">
                    Cari
                </button>
            
                {{-- Tombol Request Barang --}}
                <a href="{{ route('barang.request') }}"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm text-center">
                    ➕ Request Barang
                </a>
            </div>


            {{-- Tabel --}}
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 w-1/6">Kode</th>
                            <th class="px-4 py-3 w-1/3">Nama Barang</th>
                            <th class="px-4 py-3 w-1/4">Kategori</th>
                            <th class="px-4 py-3 w-1/6">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barang as $item)
                            <tr class="border-b hover:bg-gray-50">
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

        // Submit otomatis saat ganti kategori
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
