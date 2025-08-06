<x-app-layout>
    <x-slot name="title">Laporan Pajak Kendaraan</x-slot>

    <div class="max-w-5xl mx-auto mt-10 space-y-6">
        <!-- Judul -->
        <div class="text-center">
            <h1 class="text-2xl font-bold text-blue-800">Laporan Pajak Kendaraan</h1>
            <p class="text-sm text-gray-500">Formulir pelaporan pajak kendaraan Diskominfo</p>
        </div>

        <!-- Alert Success/Error -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow">
                {{ session('error') }}
            </div>
        @endif

        <!-- Validation Error -->
        @if($errors->any())
            <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded shadow">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Pilih Kendaraan -->
        <div class="bg-white p-6 rounded-xl shadow">
            <label class="block font-semibold text-gray-700 mb-2">Pilih Kendaraan untuk Laporan</label>
            <select id="select-kendaraan" name="kendaraan_id"
                    class="w-full border border-gray-300 rounded px-3 py-2"
                    onchange="tampilkanDetailKendaraan(this)">
                <option value="">-- Pilih Kendaraan --</option>
                @foreach($kendaraans as $k)
                    <option value="{{ $k->id }}" data-kendaraan='@json($k)'>
                        {{ $k->nama ?? '-' }} ({{ $k->no_polisi ?? 'N/A' }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Informasi Kendaraan -->
        <div id="info-kendaraan" class="bg-blue-50 p-6 rounded-xl shadow hidden">
            <h3 class="font-bold text-blue-800 mb-4">Informasi Kendaraan</h3>
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
                <div><strong>ID Kendaraan:</strong> <span id="info-id"></span></div>
                <div><strong>Nama Kendaraan:</strong> <span id="info-nama"></span></div>
                <div><strong>Nama User:</strong> <span id="info-user"></span></div>
                <div><strong>Kategori:</strong> <span id="info-kategori"></span></div>
                <div><strong>No Polisi:</strong> <span id="info-polisi"></span></div>
                <div><strong>Spesifikasi:</strong> <span id="info-spesifikasi"></span></div>
            </div>
        </div>

        <!-- Formulir Pajak -->
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Formulir Laporan Pajak</h2>

            <form action="{{ route('kendaraan.store-lapor-pajak') }}" method="POST">
                @csrf

                <!-- Hidden input kendaraan_id -->
                <input type="hidden" name="kendaraan_id" id="input-kendaraan-id">

                <!-- Jenis Pajak -->
                <div class="mb-4">
                    <label for="jenis_pajak" class="block text-sm font-medium text-gray-700">Jenis Pajak</label>
                    <select name="jenis_pajak" id="jenis_pajak" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="">-- Pilih Jenis Pajak --</option>
                        <option value="Tahunan">Tahunan</option>
                        <option value="Lima Tahunan">Lima Tahunan</option>
                    </select>
                </div>

                <!-- Tanggal Pajak -->
                <div class="mb-4">
                    <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal Pajak</label>
                    <input type="date" name="tanggal" id="tanggal"
                           class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>

                <!-- Deskripsi / Keterangan -->
                <div class="mb-4">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Keterangan</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3"
                              class="w-full border border-gray-300 rounded px-3 py-2"
                              placeholder="Contoh: Pajak kendaraan ini untuk tahun berjalan..."></textarea>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-2">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                        Simpan Laporan Pajak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script: Menampilkan detail kendaraan -->
    <script>
        function tampilkanDetailKendaraan(select) {
            const selectedOption = select.options[select.selectedIndex];
            const kendaraanData = selectedOption.getAttribute('data-kendaraan');
            const kendaraanId = selectedOption.value;

            if (kendaraanData && kendaraanId) {
                const kendaraan = JSON.parse(kendaraanData);

                document.getElementById('info-kendaraan').classList.remove('hidden');
                document.getElementById('info-id').textContent = kendaraan.id || '-';
                document.getElementById('info-nama').textContent = kendaraan.nama || '-';
                document.getElementById('info-user').textContent = kendaraan.nama_user || '-';
                document.getElementById('info-kategori').textContent = kendaraan.kategori || '-';
                document.getElementById('info-polisi').textContent = kendaraan.no_polisi || '-';
                document.getElementById('info-spesifikasi').textContent = kendaraan.spesifikasi || '-';

                document.getElementById('input-kendaraan-id').value = kendaraanId;
            } else {
                document.getElementById('info-kendaraan').classList.add('hidden');
                document.getElementById('input-kendaraan-id').value = '';
            }
        }
    </script>
</x-app-layout>
