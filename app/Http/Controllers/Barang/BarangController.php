<?php

namespace App\Http\Controllers\Barang;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\PengajuanBarang;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $kategori = Kategori::all();

        // Ambil data barang + kategori + total stok dari detail_barang
        $query = Barang::with(['kategori'])
            ->withSum('detailBarang', 'jumlah');

        // Filter nama
        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        $barang = $query
            ->withSum('detailBarang', 'jumlah')
            ->orderBy('nama_barang')
            ->get()
            ->filter(fn ($item) => $item->detail_barang_sum_jumlah > 0);

        return view('barang.index', compact('barang', 'kategori'));
    }

    public function createRequest()
    {
        $barang = Barang::withSum('detailBarang', 'jumlah')->get();
        $kategori = Kategori::all();

        return view('barang.request', compact('barang', 'kategori'));
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah_barang' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        $barang = Barang::withSum('detailBarang', 'jumlah')->findOrFail($request->barang_id);
        $stokTersedia = $barang->detail_barang_sum_jumlah ?? 0;

        $pengajuanMenunggu = PengajuanBarang::where('user_id', Auth::id())
            ->where('barang_id', $barang->id)
            ->where('status', 'Menunggu')
            ->sum('jumlah_barang');

        $stokSisa = $stokTersedia - $pengajuanMenunggu;

        if ($stokSisa < $request->jumlah_barang) {
            return back()->withErrors([
                'jumlah_barang' => 'Stok tidak mencukupi. Sisa stok tersedia (setelah dikurangi pengajuan sebelumnya): ' . $stokSisa
            ])->withInput();
        }

        PengajuanBarang::create([
            'user_id' => Auth::id(),
            'barang_id' => $barang->id,
            'jumlah_barang' => $request->jumlah_barang,
            'tanggal' => $request->tanggal,
            'status' => 'Menunggu',
        ]);

        return redirect()->route('barang.history')->with('success', 'Permintaan barang berhasil dikirim.');
    }

    public function history()
    {
        $pengajuan = PengajuanBarang::with('barang.kategori')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('barang.history', compact('pengajuan'));
    }
}
