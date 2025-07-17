<!-- Modal Detail Permintaan Barang -->
<div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm hidden">
    <div class="relative w-full max-w-md mx-auto p-6 rounded-2xl shadow-2xl"
        style="background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%); box-shadow: 10px 10px 30px rgba(0,0,0,0.1), -10px -10px 30px rgba(255,255,255,0.5);">
        
        <h3 class="text-xl font-extrabold text-slate-800 mb-5 text-center tracking-wide border-b pb-2">
            Detail Permintaan Barang
        </h3>

        <div class="space-y-3 text-[15px] text-slate-700 leading-relaxed">
            <p><span class="font-semibold">Tanggal:</span> <span id="detailTanggal"></span></p>
            <p><span class="font-semibold">Kategori:</span> <span id="detailKategori"></span></p>
            <p><span class="font-semibold">Barang:</span> <span id="detailBarang"></span></p>
            <p><span class="font-semibold">Jumlah:</span> <span id="detailJumlah"></span></p>
            <p><span class="font-semibold">Status:</span> <span id="detailStatus"></span></p>
        </div>

        <div class="text-right mt-6">
            <button onclick="closeModal()" class="px-5 py-2 font-semibold text-white rounded-lg transition-all duration-300"
                style="background: linear-gradient(135deg, #ff416c, #ff4b2b); box-shadow: 0 4px 14px rgba(255, 65, 108, 0.3);">
                Tutup
            </button>
        </div>
    </div>
</div>
