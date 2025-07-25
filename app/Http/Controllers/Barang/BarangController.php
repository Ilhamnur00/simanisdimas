<?php

namespace App\Http\Controllers\Barang;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\TransaksiBarang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::with('detailBarang')->get();
        return view('barang.index', compact('barang'));
    }

    public function createRequest()
    {
        $barang = Barang::all();
        $kategori = Kategori::all();
        return view('barang.request', compact('barang', 'kategori'));
    }

    public function storeRequest()
    {
        try {
            DB::transaction(function () {
                TransaksiBarang::create([
                    'barang_id' => request('barang_id'),
                    'jenis_transaksi' => request('jenis_transaksi'),
                    'jumlah_barang' => request('jumlah_barang'),
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
