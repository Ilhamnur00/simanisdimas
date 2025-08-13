<?php

namespace App\Http\Controllers\Barang;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\TransaksiBarang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request; // tambahkan di atas

class BarangController extends Controller
{
public function index(Request $request)
{
    $barang = Barang::with('kategori')
        ->select('barang.*')
        ->selectRaw('(SELECT SUM(jumlah) FROM detail_barang WHERE detail_barang.barang_id = barang.id) as stok')
        ->when($request->search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        })
        ->when($request->kategori, function ($query, $kategori) {
            $query->where('kategori_id', $kategori);
        })
        ->having('stok', '>', 0)
        ->orderBy('nama_barang')
        ->get();

    return view('barang.index', compact('barang'));
}


public function createRequest()
{
    // Ambil hanya barang yang masih ada stok
    $barang = Barang::all()->filter(function ($item) {
        return $item->stok > 0;
    });
    $kategori = Kategori::all();
    return view('barang.request', compact('barang', 'kategori'));
}

    public function storeRequest()
    {
        try {
            $barang = Barang::findOrFail(request('barang_id'));
            $jenisTransaksi = request('jenis_transaksi');
            $jumlahBarang = (int) request('jumlah_barang');

            // Cek stok jika transaksi keluar
            if ($jenisTransaksi === 'keluar' && $barang->stok < $jumlahBarang) {
                return redirect()->back()
                    ->with('error', 'Stok barang tidak mencukupi untuk transaksi keluar.')
                    ->withInput();
            }

            DB::transaction(function () use ($barang, $jenisTransaksi, $jumlahBarang) {
                TransaksiBarang::create([
                    'barang_id' => $barang->id,
                    'jenis_transaksi' => $jenisTransaksi,
                    'jumlah_barang' => $jumlahBarang,
                    'tanggal' => now(),
                    'user_id' => Auth::id(),
                ]);
            });

            return redirect()->route('barang.index')->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }


    public function history()
    {
        $transaksi = TransaksiBarang::with(['detailBarang.barang.kategori'])
            ->where('user_id', Auth::id())
            ->where('jenis_transaksi', 'keluar')
            ->latest()
            ->get();

        return view('barang.history', compact('transaksi'));
    }

}
