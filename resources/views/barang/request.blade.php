<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🛒 Permintaan Barang
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi berhasil --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Notifikasi error --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('barang.request.store') }}" method="POST"
                class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 space-y-4">
                @csrf

                {{-- Kategori --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="kategori" id="kategori" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Pilih --</option>
                        @foreach ($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Barang --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Barang</label>
                    <select name="barang_id" id="barang_id" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Pilih Kategori Dulu --</option>
                        @foreach ($barang as $b)
                            <option value="{{ $b->id }}" data-kategori="{{ $b->kategori_id }}"
                                {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Jumlah --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                    <input type="number" name="jumlah_barang" id="jumlah" class="w-full border rounded px-3 py-2"
                        required min="1" value="{{ old('jumlah_barang', 1) }}">
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" class="w-full border rounded px-3 py-2"
                        value="{{ old('tanggal') }}" required>
                </div>

                {{-- Tombol Submit --}}
                <div class="text-right pt-4">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">
                        Kirim Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script filter barang sesuai kategori --}}
    <script>
        const kategoriSelect = document.getElementById('kategori');
        const barangSelect = document.getElementById('barang_id');
        const allBarangOption = Array.from(barangSelect.options);

        kategoriSelect.addEventListener('change', function () {
            const selected = this.value;

            barangSelect.innerHTML = '';
            const filtered = allBarangOption.filter(opt => opt.dataset.kategori == selected);

            if (filtered.length > 0) {
                barangSelect.innerHTML = '<option value="">-- Pilih --</option>';
                filtered.forEach(opt => barangSelect.appendChild(opt));
            } else {
                barangSelect.innerHTML = '<option value="">Tidak ada barang</option>';
            }
        });

        // Auto-filter if old value exists (on validation error)
        window.addEventListener('DOMContentLoaded', () => {
            const selectedKategori = kategoriSelect.value;
            const oldBarangId = "{{ old('barang_id') }}";
            if (selectedKategori) {
                const filtered = allBarangOption.filter(opt => opt.dataset.kategori == selectedKategori);
                barangSelect.innerHTML = '<option value="">-- Pilih --</option>';
                filtered.forEach(opt => {
                    if (opt.value === oldBarangId) opt.selected = true;
                    barangSelect.appendChild(opt);
                });
            }
        });
    </script>
</x-app-layout>
