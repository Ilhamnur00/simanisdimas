<?php

namespace App\Http\Controllers\Barang;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\TransaksiBarang;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use app\Http\Controllers\Controller;

class BarangController extends Controller
{
    /**
     * 1. Tampilkan daftar stok barang (untuk user)
     */
    public function index(Request $request)
    {
        $kategori = Kategori::all();

        $query = Barang::with('kategori');

        // Filter berdasarkan nama barang
        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        $barang = $query->get();

        return view('barang.index', compact('barang', 'kategori'));
    }

    /**
     * 2. Tampilkan form permintaan barang (user)
     */
    public function createRequest()
    {
        $barang = Barang::all();
        $kategori = Kategori::all();

        return view('barang.request', compact('barang', 'kategori'));
    }

    /**
     * 3. Simpan permintaan barang (user)
     */
    public function storeRequest(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id', // gunakan nama tabel yang sesuai
            'jumlah_barang' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        TransaksiBarang::create([
            'user_id' => Auth::id(),
            'barang_id' => $request->barang_id,
            'jumlah_barang' => $request->jumlah_barang,
            'jenis_transaksi' => 'keluar',
            'tanggal' => $request->tanggal,
            'status' => 'pending',
        ]);

        return redirect()->route('barang.history')
            ->with('success', 'Permintaan barang berhasil dikirim.');
    }

    /**
     * 4. Tampilkan riwayat permintaan barang user
     */
    public function history()
    {
        $transaksi = TransaksiBarang::with('barang.kategori')
            ->where('user_id', Auth::id())
            ->orderByDesc('tanggal')
            ->get();

        return view('barang.history', compact('transaksi'));
    }
}
