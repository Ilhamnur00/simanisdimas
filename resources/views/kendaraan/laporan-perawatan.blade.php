<x-app-layout>
    <x-slot name="title">Laporan Perawatan Kendaraan</x-slot>

    <div class="max-w-5xl mx-auto mt-10 space-y-6">
        <!-- Judul -->
        <div class="text-center">
            <h1 class="text-2xl font-bold text-blue-800">Laporan Perawatan Kendaraan</h1>
            <p class="text-sm text-gray-500">Formulir pelaporan perawatan kendaraan Diskominfo</p>
        </div>

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

        <!-- Formulir Perawatan -->
        <div id="form-laporan" class="bg-white p-6 rounded-xl shadow hidden">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Formulir Laporan Perawatan</h2>

            <form method="POST" enctype="multipart/form-data" action="{{ route('kendaraan.laporan-perawatan.store') }}">
                @csrf
                <input type="hidden" name="kendaraan_id" id="input-kendaraan-id">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Perawatan</label>
                        <input type="date" name="tanggal" id="tanggal" required class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="kategori_perawatan" class="block text-sm font-medium text-gray-700 mb-1">Kategori Perawatan</label>
                        <select name="kategori_perawatan" id="kategori_perawatan" required class="w-full border border-gray-300 rounded px-3 py-2">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Service">Service</option>
                            <option value="Ganti Oli">Ganti Oli</option>
                            <option value="Perbaikan">Perbaikan</option>
                        </select>
                    </div>

                    <!-- Bukti Upload -->
                    <div class="sm:col-span-2">
                        <label for="bukti" class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti (opsional)</label>
                        <input type="file" name="bukti" id="bukti" accept=".jpg,.jpeg,.png,.pdf"
                               class="w-full border border-gray-300 rounded px-3 py-2"
                               onchange="previewLampiran(event)">
                        <div id="preview-container" class="mt-3 hidden">
                            <img id="preview-image" class="max-w-xs rounded border shadow">
                            <button type="button" onclick="hapusLampiran()"
                                    class="mt-2 px-4 py-1 bg-red-600 text-white text-sm rounded">
                                Hapus File
                            </button>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="sm:col-span-2">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Perawatan</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4"
                                  class="w-full border border-gray-300 rounded px-3 py-2"
                                  placeholder="Contoh: Ganti oli, perawatan rutin, dll" required></textarea>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-6 text-right">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded">
                        Simpan Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script -->
    <script>
        function tampilkanDetailKendaraan(select) {
            const selectedOption = select.options[select.selectedIndex];
            const kendaraanData = selectedOption.getAttribute('data-kendaraan');
            const kendaraanId = selectedOption.value;

            if (kendaraanData && kendaraanId) {
                const kendaraan = JSON.parse(kendaraanData);

                document.getElementById('info-id').textContent = kendaraan.id || '-';
                document.getElementById('info-nama').textContent = kendaraan.nama || '-';
                document.getElementById('info-user').textContent = kendaraan.user?.name || '-';
                document.getElementById('info-kategori').textContent = kendaraan.kategori || '-';
                document.getElementById('info-polisi').textContent = kendaraan.no_polisi || '-';
                document.getElementById('info-spesifikasi').textContent = kendaraan.spesifikasi || '-';

                document.getElementById('input-kendaraan-id').value = kendaraanId;

                document.getElementById('info-kendaraan').classList.remove('hidden');
                document.getElementById('form-laporan').classList.remove('hidden');
            } else {
                document.getElementById('info-kendaraan').classList.add('hidden');
                document.getElementById('form-laporan').classList.add('hidden');
                document.getElementById('input-kendaraan-id').value = '';
            }
        }

        function previewLampiran(event) {
            const file = event.target.files[0];
            const previewImage = document.getElementById('preview-image');
            const previewContainer = document.getElementById('preview-container');

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('hidden');
                previewImage.src = '';
            }
        }

        function hapusLampiran() {
            document.getElementById('bukti').value = '';
            document.getElementById('preview-container').classList.add('hidden');
        }
    </script>
</x-app-layout>
