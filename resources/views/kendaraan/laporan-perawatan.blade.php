<x-app-layout>
    <x-slot name="title">Laporan Perawatan Kendaraan</x-slot>
    
    <div class="max-w-5xl mx-auto mt-10 space-y-6">
         <!-- Header -->
        <div class="mb-10 text-center">
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-[#003973] via-[#2980B9] to-[#6DD5FA] drop-shadow">Laporan Perawatan Kendaraan Dinas</h2>
            <p class="text-slate-500 mt-2 text-lg">Formulir pelaporan untuk perawatan kendaraan dinas Diskominfo</p>
        </div>

        <!-- Flash Message -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <strong class="font-bold">Sukses!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
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

        <!-- Formulir Perawatan -->
        <div id="form-laporan" class="bg-white p-6 rounded-xl shadow hidden">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Formulir Laporan Perawatan</h2>

            <form method="POST" enctype="multipart/form-data" action="{{ route('kendaraan.laporan-perawatan.store') }}" onsubmit="return validateForm()">
                @csrf
                <input type="hidden" name="kendaraan_id" id="input-kendaraan-id">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Perawatan</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}" required class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="kategori_perawatan" class="block text-sm font-medium text-gray-700 mb-1">Kategori Perawatan</label>
                        <select name="kategori_perawatan" id="kategori_perawatan" required class="w-full border border-gray-300 rounded px-3 py-2">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Service" {{ old('kategori_perawatan') == 'Service' ? 'selected' : '' }}>Service</option>
                            <option value="Ganti Oli" {{ old('kategori_perawatan') == 'Ganti Oli' ? 'selected' : '' }}>Ganti Oli</option>
                            <option value="Perbaikan" {{ old('kategori_perawatan') == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                        </select>
                    </div>

                    <!-- Bukti Upload -->
                    <div class="sm:col-span-2">
                        <label for="bukti" class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti (opsional)</label>
                        <input type="file" name="bukti" id="bukti" accept=".jpg,.jpeg,.png,.pdf"
                               class="w-full border border-gray-300 rounded px-3 py-2"
                               onchange="previewLampiran(event)">
                        <div id="preview-container" class="mt-3 hidden">
                            <img id="preview-image" class="max-w-xs rounded border shadow hidden">
                            <p id="preview-filename" class="text-sm text-gray-700"></p>
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
                                  placeholder="Contoh: Ganti oli, perawatan rutin, dll" required>{{ old('deskripsi') }}</textarea>
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
            const previewFilename = document.getElementById('preview-filename');
            const previewContainer = document.getElementById('preview-container');

            const validTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            const maxSize = 2 * 1024 * 1024; // 2MB

            if (file) {
                if (!validTypes.includes(file.type)) {
                    alert("File harus berupa .jpg, .jpeg, .png, atau .pdf");
                    event.target.value = '';
                    return;
                }

                if (file.size > maxSize) {
                    alert("Ukuran file maksimal 2MB.");
                    event.target.value = '';
                    return;
                }

                previewContainer.classList.remove('hidden');
                previewFilename.textContent = file.name;

                if (file.type.startsWith('image/')) {
                    previewImage.src = URL.createObjectURL(file);
                    previewImage.classList.remove('hidden');
                } else {
                    previewImage.src = '';
                    previewImage.classList.add('hidden');
                }
            } else {
                previewContainer.classList.add('hidden');
            }
        }

        function hapusLampiran() {
            document.getElementById('bukti').value = '';
            document.getElementById('preview-image').src = '';
            document.getElementById('preview-image').classList.add('hidden');
            document.getElementById('preview-container').classList.add('hidden');
            document.getElementById('preview-filename').textContent = '';
        }

        function validateForm() {
            const kendaraanId = document.getElementById('select-kendaraan').value;
            if (!kendaraanId) {
                alert("Silakan pilih kendaraan terlebih dahulu.");
                return false;
            }
            return true;
        }
    </script>
</x-app-layout>
