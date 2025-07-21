<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Kendaraan Dinas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">Transaksi - Kendaraan Dinas</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">+ Tambahkan Pengajuan</button>

        <table class="min-w-full bg-white rounded">
            <thead>
                <tr>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Perawatan</th>
                    <th class="border px-4 py-2">Tanggal Pengajuan</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                    <tr>
                        <td class="border px-4 py-2">{{ $item['id'] }}</td>
                        <td class="border px-4 py-2">{{ $item['perawatan'] }}</td>
                        <td class="border px-4 py-2">{{ $item['tanggal_pengajuan'] }}</td>
                        <td class="border px-4 py-2">
                            <button onclick="showDetail({{ json_encode($item) }})" class="bg-gray-500 text-white px-3 py-1 rounded">Detail</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-500 p-4">Belum ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal Tambah --}}
    <div id="modalTambah" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white w-full max-w-lg p-6 rounded shadow">
            <h2 class="text-xl font-bold mb-4">Form Pengajuan</h2>
            <form action="{{ route('transaksi.kendaraan.store') }}" method="POST">
                @csrf
                <div class="mb-2">
                    <label class="block text-sm">Jenis Kendaraan</label>
                    <select name="jenis_kendaraan" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Pilih --</option>
                        <option value="motor">Motor</option>
                        <option value="mobil">Mobil</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="block text-sm">Nama Kendaraan</label>
                    <input type="text" name="nama_kendaraan" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-2">
                    <label class="block text-sm">Nama Pengguna</label>
                    <input type="text" name="nama_pengguna" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-2">
                    <label class="block text-sm">Tanggal Pajak</label>
                    <input type="date" name="pajak" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-2">
                    <label class="block text-sm">Perawatan</label>
                    <input type="text" name="perawatan" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-2">
                    <label class="block text-sm">Tanggal Pengajuan</label>
                    <input type="date" name="tanggal_pengajuan" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="flex justify-end mt-4 space-x-2">
                    <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Detail --}}
    <div id="modalDetail" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white w-full max-w-xl p-6 rounded shadow">
            <h2 class="text-xl font-bold mb-4">Detail Pengajuan</h2>
            <div class="space-y-2">
                <p><strong>Jenis Kendaraan:</strong> <span id="d_jenis_kendaraan"></span></p>
                <p><strong>Nama Kendaraan:</strong> <span id="d_nama_kendaraan"></span></p>
                <p><strong>Nama Pengguna:</strong> <span id="d_nama_pengguna"></span></p>
                <p><strong>Pajak:</strong> <span id="d_pajak"></span></p>
                <p><strong>Status Pajak:</strong> <span id="d_status_pajak"></span></p>
                <p><strong>Perawatan:</strong> <span id="d_perawatan"></span></p>
                <p><strong>Tanggal Pengajuan:</strong> <span id="d_tanggal_pengajuan"></span></p>
            </div>
            <div class="flex justify-end mt-4">
                <button onclick="document.getElementById('modalDetail').classList.add('hidden')" class="px-4 py-2 bg-gray-300 rounded">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        function showDetail(data) {
            document.getElementById('d_jenis_kendaraan').innerText = data.jenis_kendaraan;
            document.getElementById('d_nama_kendaraan').innerText = data.nama_kendaraan;
            document.getElementById('d_nama_pengguna').innerText = data.nama_pengguna;
            document.getElementById('d_pajak').innerText = data.pajak;
            document.getElementById('d_status_pajak').innerText = data.status_pajak;
            document.getElementById('d_perawatan').innerText = data.perawatan;
            document.getElementById('d_tanggal_pengajuan').innerText = data.tanggal_pengajuan;
            document.getElementById('modalDetail').classList.remove('hidden');
        }
    </script>
</body>
</html>
