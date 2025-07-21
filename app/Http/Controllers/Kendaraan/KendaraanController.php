<?php

namespace App\Http\Controllers\Kendaraan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kendaraan;



class KendaraanController extends Controller
{
    // Menampilkan halaman utama Data Master Kendaraan
    public function index()
    {
        $kendaraans = Kendaraan::latest()->get();
        return view('datamaster.kendaraan.index', compact('kendaraans'));
    }

    // Menyimpan data kendaraan baru dari form Tambah (via AJAX)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_transaksi'   => 'required|string',
            'tanggal'           => 'required|date',
            'jenis_kendaraan'   => 'required|string',
            'nama_kendaraan'    => 'required|string',
            'pengguna'          => 'required|string',
            'status_pajak'      => 'required|string',
            'perawatan'         => 'nullable|string',
            'bulan'             => 'required|string',
        ]);

        $kendaraan = Kendaraan::create($validated);

        // Return response JSON agar bisa ditangkap oleh JavaScript (AJAX)
        return response()->json([
            'message' => 'Data berhasil disimpan!',
            'id' => $kendaraan->id,
            'pengguna' => $kendaraan->pengguna,
            'tanggal' => $kendaraan->tanggal,
        ]);
    }

    // Mengupdate data kendaraan dari modal edit (via AJAX)
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jenis_transaksi'   => 'required|string',
            'tanggal'           => 'required|date',
            'jenis_kendaraan'   => 'required|string',
            'nama_kendaraan'    => 'required|string',
            'pengguna'          => 'required|string',
            'status_pajak'      => 'required|string',
            'perawatan'         => 'nullable|string',
            'bulan'             => 'required|string',
        ]);

        $kendaraan = Kendaraan::findOrFail($id);
        $kendaraan->update($validated);

        return response()->json([
            'message' => 'Data berhasil diperbarui!',
        ]);
    }

    // Menghapus data kendaraan (via AJAX)
    public function destroy($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $kendaraan->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus!',
        ]);
    }
}
