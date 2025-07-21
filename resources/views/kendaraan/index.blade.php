<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Data Kendaraan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs" defer></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="flex">

  <!-- Sidebar -->
  <aside class="w-60 h-screen bg-blue-900 text-white p-4">
    <h1 class="text-2xl font-bold mb-8">SIMANISDIMAS</h1>
    <nav class="space-y-4">
      <a href="#" class="block hover:text-blue-300">Dashboard</a>
      <a href="#" class="block bg-blue-700 p-2 rounded">Data Master</a>
      <a href="#" class="block hover:text-blue-300">Transaksi</a>
      <a href="#" class="block hover:text-blue-300">Laporan</a>
      <a href="#" class="block hover:text-blue-300">Profil</a>
    </nav>
  </aside>

  <!-- Main -->
  <main class="flex-1 p-6 bg-gray-100" x-data="kendaraanData()" x-init="initData()">
    <h2 class="text-2xl font-bold mb-4">Data Kendaraan</h2>

    <!-- Search + Add -->
    <div class="flex justify-between mb-4">
      <div class="flex gap-2">
        <input type="text" x-model="searchId" placeholder="Cari berdasarkan ID" class="border rounded px-2 py-1">
        <button @click="filterData" class="bg-gray-600 text-white px-4 py-1 rounded">Cari</button>
      </div>
      <button @click="openModal('add')" class="bg-green-600 text-white px-4 py-1 rounded">+ Tambah Data</button>
    </div>

    <!-- Tabel -->
    <table class="w-full bg-white rounded shadow">
      <thead class="bg-gray-200">
        <tr>
          <th class="p-2 border">ID</th>
          <th class="p-2 border">Nama Pengguna</th>
          <th class="p-2 border">Tanggal Pajak</th>
          <th class="p-2 border">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <template x-for="(item, index) in filteredData" :key="item.id">
          <tr>
            <td class="p-2 border" x-text="item.id"></td>
            <td class="p-2 border" x-text="item.pengguna"></td>
            <td class="p-2 border" x-text="item.tanggal"></td>
            <td class="p-2 border text-center">
              <button @click="editData(index)" class="bg-blue-600 text-white px-3 py-1 rounded">Edit</button>
            </td>
          </tr>
        </template>
        <tr x-show="filteredData.length === 0">
          <td colspan="4" class="text-center py-4 text-gray-500">Tidak ada data</td>
        </tr>
      </tbody>
    </table>

    <!-- Modal -->
    <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50" x-show="modalOpen">
      <div class="bg-white p-6 rounded w-full max-w-md relative">
        <button @click="modalOpen = false" class="absolute top-2 right-3 text-xl text-gray-500">×</button>
        <h3 class="text-lg font-bold mb-4" x-text="editIndex === null ? 'Tambah Data' : 'Edit Data'"></h3>

        <div class="space-y-3">
          <div>
            <label class="block font-medium">Nama Pengguna</label>
            <input type="text" x-model="form.pengguna" class="w-full border px-3 py-2 rounded" required>
          </div>
          <div>
            <label class="block font-medium">Tanggal Pajak</label>
            <input type="date" x-model="form.tanggal" class="w-full border px-3 py-2 rounded" required>
          </div>
          <div>
            <label class="block font-medium">Jenis Kendaraan</label>
            <select x-model="form.jenis_kendaraan" class="w-full border px-3 py-2 rounded" required>
              <option value="">-- Pilih Jenis --</option>
              <option value="Motor">Motor</option>
              <option value="Mobil">Mobil</option>
            </select>
          </div>
        </div>

        <div class="mt-5 flex justify-between gap-3">
          <template x-if="editIndex !== null">
            <button @click="deleteData(data[editIndex].id)" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Hapus</button>
          </template>
          <button @click="submitForm" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </div>
      </div>
    </div>
  </main>

  <script>
    function kendaraanData() {
      return {
        data: @json($kendaraans),
        filteredData: [],
        searchId: '',
        modalOpen: false,
        editIndex: null,
        form: {
          pengguna: '',
          tanggal: '',
          jenis_kendaraan: '',
        },

        initData() {
          this.filteredData = this.data;
        },

        openModal(mode) {
          this.modalOpen = true;
          if (mode === 'add') {
            this.editIndex = null;
            this.form = {
              pengguna: '',
              tanggal: '',
              jenis_kendaraan: '',
            };
          }
        },

        editData(index) {
          this.editIndex = index;
          this.form = { ...this.data[index] };
          this.modalOpen = true;
        },

        deleteData(id) {
          if (!confirm('Yakin ingin menghapus data ini?')) return;

          fetch(`/data-master/kendaraan/${id}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          }).then(() => {
            this.data = this.data.filter(item => item.id !== id);
            this.filteredData = this.data;
            this.modalOpen = false;
          });
        },

        getStatus(tanggal) {
          const today = new Date();
          const pajakDate = new Date(tanggal);
          return pajakDate >= today ? 'Aktif' : 'Tidak Aktif';
        },

        filterData() {
          if (!this.searchId) {
            this.filteredData = this.data;
          } else {
            this.filteredData = this.data.filter(item => item.id == this.searchId);
          }
        },

        submitForm() {
          const isEdit = this.editIndex !== null;
          const url = isEdit
            ? `/data-master/kendaraan/${this.data[this.editIndex].id}`
            : `{{ route('kendaraan.store') }}`;
          const method = isEdit ? 'PUT' : 'POST';

          const payload = {
            ...this.form,
            jenis_transaksi: 'Servis',
            nama_kendaraan: 'Toyota Avanza',
            perawatan: 'Ganti Oli',
            status_pajak: this.getStatus(this.form.tanggal),
            bulan: new Date(this.form.tanggal).toLocaleString('default', { month: 'long' })
          };

          fetch(url, {
            method: method,
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(payload)
          })
          .then(res => res.json())
          .then(res => {
            if (isEdit) {
              this.data[this.editIndex] = { ...this.data[this.editIndex], ...payload };
            } else {
              this.data.push({ id: res.id ?? new Date().getTime(), ...payload });
            }
            this.filteredData = this.data;
            this.modalOpen = false;
          });
        }
      }
    }
  </script>

</body>
</html>
