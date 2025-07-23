<x-app-layout>
    <x-slot name="title">
        Laporan Perawatan Kendaraan
    </x-slot>

    <div class="max-w-5xl mx-auto py-12 px-6">
        <!-- Header -->
        <div class="mb-10 text-center">
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-[#003973] via-[#2980B9] to-[#6DD5FA] drop-shadow">Laporan Perawatan Kendaraan</h2>
            <p class="text-slate-500 mt-2 text-lg">Formulir pelaporan untuk perawatan Kendaraan Diskominfo</p>
        </div>

        <!-- Informasi Kendaraan -->
        <div class="bg-gradient-to-br from-sky-50 to-white border border-sky-100 shadow-md rounded-2xl p-6 mb-10">
            <h3 class="text-xl font-semibold text-sky-800 mb-4">Informasi Kendaraan</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 text-sm text-slate-800">
                <p><span class="font-medium">ID Kendaraan:</span> {{ $kendaraan->id }}</p>
                <p><span class="font-medium">Nama Kendaraan:</span> {{ $kendaraan->nama }}</p>
                <p><span class="font-medium">Nama User:</span> {{ $kendaraan->user->name ?? '-' }}</p>
                <p><span class="font-medium">Kategori:</span> {{ $kendaraan->kategori ?? '-' }}</p>
                <p><span class="font-medium">Spesifikasi:</span> {{ $kendaraan->spesifikasi }}</p>
                <p class="sm:col-span-2">
                    <span class="font-medium">Tanggal Serah Terima:</span>
                    {{ $kendaraan->tanggal_serah_terima ? \Carbon\Carbon::parse($kendaraan->tanggal_serah_terima)->translatedFormat('d F Y') : '-' }}
                </p>
            </div>
        </div>

        <!-- Formulir Laporan -->
        <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-xl">
            <h4 class="text-2xl font-semibold text-sky-800 mb-6">Formulir Laporan Perawatan Kendaraan</h4>

            <form method="POST" action="{{ route('maintenance.store', $device->id) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Perawatan Kendaraan</label>
                        <input type="date" name="tanggal" id="tanggal" required
                            class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-100 transition">
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="kategori_perawatan" class="block text-sm font-medium text-slate-700 mb-1">Kategori Perawatan Kendaraan</label>
                        <select name="kategori_perawatan" id="kategori_perawatan" required
                            class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-100 transition">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Maintenance">Maintenance (Perawatan Berkala)</option>
                            <option value="Ganti part">Ganti part</option>
                            <option value="Sparepart Replacement">Penggantian Part (Sparepart Replacement)</option>
                            <option value="Troubleshooting Hardware">Troubleshooting Hardware</option>
                            <option value="Troubleshooting Software">Troubleshooting Software</option>
                            <option value="Upgrade">Upgrade</option>
                            <option value="Instalasi Ulang">Instalasi Ulang</option>
                            <option value="Data Recovery & Backup">Data Recovery & Backup</option>
                            <option value="Kendala Konektivitas">Kendala Konektivitas</option>
                            <option value="Masalah Audio/Visual">Masalah Audio/Visual</option>
                            <option value="Masalah Daya">Masalah Daya</option>
                        </select>
                    </div>

                    <!-- Lampiran -->
                    <div class="sm:col-span-2">
                        <label for="bukti" class="block text-sm font-medium text-slate-700 mb-1">Upload Bukti Perawatan (PDF/JPG/PNG)</label>
                        <input type="file" name="bukti" id="bukti" accept=".pdf,.jpg,.jpeg,.png"
                            class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-100 transition"
                            onchange="previewLampiran(event)">

                        <!-- Preview -->
                        <div id="preview-container" class="mt-4 hidden">
                            <p class="text-sm text-slate-600 mb-1">Preview Gambar:</p>
                            <img id="preview-image" class="max-w-xs rounded-lg border border-slate-300 shadow-md" />
                            <button type="button" onclick="hapusLampiran()"
                                class="mt-2 px-4 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition">
                                Hapus File
                            </button>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="sm:col-span-2">
                        <label for="deskripsi" class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Perawatan</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" required
                            class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-100 transition"
                            placeholder="Jelaskan kondisi perangkat atau kendala yang dialami..."></textarea>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-6 text-right">
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-6 py-2 rounded-md shadow-md hover:opacity-90 transition text-sm">
                        Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JS Preview -->
    <script>
        function previewLampiran(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('preview-container');
            const previewImage = document.getElementById('preview-image');

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
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
            const fileInput = document.getElementById('bukti');
            const previewContainer = document.getElementById('preview-container');
            const previewImage = document.getElementById('preview-image');

            fileInput.value = '';
            previewImage.src = '';
            previewContainer.classList.add('hidden');
        }
    </script>
</x-app-layout>
