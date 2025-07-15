<div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white w-full max-w-md mx-auto rounded shadow-lg p-6 relative">
        <h3 class="text-lg font-bold mb-4">📦 Detail Permintaan Barang</h3>

        <div class="space-y-2 text-sm text-gray-700">
            <p><strong>Tanggal:</strong> <span id="detailTanggal"></span></p>
            <p><strong>Kategori:</strong> <span id="detailKategori"></span></p>
            <p><strong>Barang:</strong> <span id="detailBarang"></span></p>
            <p><strong>Jumlah:</strong> <span id="detailJumlah"></span></p>
            <p><strong>Status:</strong> <span id="detailStatus"></span></p>
        </div>

        <div class="text-right mt-4">
            <button onclick="closeModal()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                Tutup
            </button>
        </div>
    </div>
</div>
