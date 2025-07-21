<?php

namespace App\Http\Controllers\Barang;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\PengajuanBarang; // gunakan model baru
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


class BarangController extends Controller
{
    public function index(Request $request)
    {
        $kategori = Kategori::all();
        $query = Barang::with('kategori');

        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        $barang = $query->get();

        return view('barang.index', compact('barang', 'kategori'));
    }

    public function createRequest()
    {
        $barang = Barang::all();
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

        $barang = Barang::findOrFail($request->barang_id);
        $stokTersedia = $barang->stok; // Ambil langsung dari kolom stok di tabel barang

        if ($stokTersedia < $request->jumlah_barang) {
            return back()->withErrors([
                'jumlah_barang' => 'Stok barang tidak mencukupi. Stok tersedia: ' . $stokTersedia
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
