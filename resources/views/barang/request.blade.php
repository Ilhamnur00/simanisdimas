<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-sky-900 via-indigo-900 to-slate-800">
            Transaksi Barang Keluar
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- FORM --}}
            <form action="{{ route('barang.request.store') }}" method="POST"
                class="bg-white/80 border border-slate-200 shadow-2xl backdrop-blur-sm rounded-2xl px-10 pt-8 pb-10 space-y-8 transition-all duration-300 ease-in-out">
                @csrf

                {{-- jenis_transaksi --}}
                <input type="hidden" name="jenis_transaksi" value="keluar">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Kategori --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-1">Kategori</label>
                        <select id="kategori"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition-all">
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
                        <label class="block text-sm font-semibold text-gray-800 mb-1">Barang</label>
                        <select name="barang_id" id="barang_id"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition-all"
                            required onchange="tampilkanStok(this)">
                            <option value="">-- Pilih Kategori Dulu --</option>
                            @foreach ($barang as $b)
                                <option 
                                    value="{{ $b->id }}" 
                                    data-kategori="{{ $b->kategori_id }}"
                                    data-stok="{{ $b->stok }}"
                                    {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->nama_barang }}
                                </option>
                            @endforeach
                        </select>

                        <div class="text-sm text-gray-600 mt-2" id="stok_info">
                            Stok tersedia: <span id="stok_tersedia">-</span>
                        </div>
                    </div>

                    {{-- Jumlah --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-1">Jumlah</label>
                        <input type="number" name="jumlah_barang" id="jumlah"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition-all"
                            required min="1" value="{{ old('jumlah_barang', 1) }}">
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-1">Tanggal</label>
                        <input type="date" name="tanggal"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition-all"
                            value="{{ old('tanggal', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="text-right pt-6">
                    <button type="submit"
                        class="bg-gradient-to-r from-sky-700 to-sky-500 text-white px-6 py-2 rounded-md shadow-md hover:from-sky-800 hover:to-sky-600 transition text-sm">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- SweetAlert2 Notifikasi --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33',
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: `{!! implode('<br>', $errors->all()) !!}`,
            });
        </script>
    @endif

    {{-- SCRIPT: Filter Barang & Tampilkan Stok --}}
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

        const stokSpan = document.getElementById('stok_tersedia');
        function tampilkanStok(select) {
            const stok = select.options[select.selectedIndex]?.dataset?.stok ?? '-';
            stokSpan.textContent = `${stok} unit`;
        }

        window.addEventListener('DOMContentLoaded', () => {
            const selectedOption = document.querySelector('#barang_id option:checked');
            if (selectedOption && selectedOption.dataset.stok) {
                tampilkanStok(document.getElementById('barang_id'));
            }
        });
    </script>
</x-app-layout>
