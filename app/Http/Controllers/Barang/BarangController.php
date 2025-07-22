<?php

namespace App\Http\Controllers\Barang;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\DetailBarang;
use App\Models\TransaksiBarang;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    /**
     * Menampilkan daftar barang kepada user
     */
    public function index(Request $request)
    {
        $kategori = Kategori::all();

        $barang = Barang::with(['kategori', 'detailBarang', 'transaksiBarang'])
            ->when($request->filled('search'), fn ($q) =>
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
            )
            ->when($request->filled('kategori'), fn ($q) =>
                $q->where('kategori_id', $request->kategori)
            )
            ->get()
            ->filter(fn ($item) => $item->stok > 0); // Gunakan accessor stok

        return view('barang.index', compact('barang', 'kategori'));
    }

    /**
     * Menampilkan form permintaan barang
     */
    public function createRequest()
    {
        $kategori = Kategori::all();
        $barang = Barang::with('kategori', 'detailBarang', 'transaksiBarang')
            ->get()
            ->filter(fn ($item) => $item->stok > 0);

        return view('barang.request', compact('kategori', 'barang'));
    }

    /**
     * Proses permintaan barang (transaksi keluar)
     */
    public function storeRequest(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah_barang' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        $barang = Barang::with(['detailBarang' => fn ($q) => $q->orderBy('created_at')])
            ->findOrFail($request->barang_id);

        if ($barang->stok < $request->jumlah_barang) {
            return back()->withErrors([
                'jumlah_barang' => 'Stok tidak mencukupi. Stok tersedia saat ini: ' . $barang->stok
            ])->withInput();
        }

        DB::beginTransaction();
        try {
            $sisa = $request->jumlah_barang;

            foreach ($barang->detailBarang as $detail) {
                if ($sisa <= 0) break;

                $ambil = min($sisa, $detail->jumlah);

                // Kurangi stok dari detail_barang
                $detail->jumlah -= $ambil;
                $detail->save();

                // Catat transaksi keluar (tanpa membuat detail_barang baru!)
                TransaksiBarang::create([
                    'barang_id' => $barang->id,
                    'user_id' => Auth::id(),
                    'detail_barang_id' => $detail->id,
                    'jenis_transaksi' => 'keluar',
                    'jumlah_barang' => $ambil,
                    'tanggal' => $request->tanggal,
                    'status' => 'Disetujui',
                ]);

                $sisa -= $ambil;
            }

            DB::commit();
            return redirect()->route('barang.history')->with('success', 'Permintaan barang berhasil diproses.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memproses transaksi.'])->withInput();
        }
    }

    /**
     * Menampilkan riwayat transaksi keluar user
     */
    public function history()
    {
        $transaksi = TransaksiBarang::with('detailBarang.barang.kategori')
            ->where('user_id', Auth::id())
            ->where('jenis_transaksi', 'keluar')
            ->latest()
            ->get();

        return view('barang.history', compact('transaksi'));
    }
}
