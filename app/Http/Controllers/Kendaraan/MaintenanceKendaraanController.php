<?php

namespace App\Http\Controllers\Kendaraan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Models\Kendaraan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaintenanceKendaraanController extends Controller
{
    /**
     * Simpan laporan perawatan baru.
     */
    public function store(Request $request, $kendaraanId)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori_perawatan' => 'required|string',
            'deskripsi' => 'required|string',
            'bukti' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('bukti')) {
            $path = $request->file('bukti')->store('bukti_perawatankendaraan', 'public');
        }

        Maintenance::create([
            'kendaraan_id' => $kendaraanId,
            'tanggal' => $request->tanggal,
            'kategori_perawatan' => $request->kategori_perawatan,
            'deskripsi' => $request->deskripsi,
            'bukti' => $path,
        ]);

        return redirect()->route('kendaraan.riwayatAll')
                 ->with('success', 'Laporan perawatan Kendaraan berhasil disimpan.');

    }

    /**
     * Tampilkan riwayat semua perawatan milik user, dengan filter kendaraan opsional.
     */
    public function riwayatAll(Request $request)
    {
        $user = Auth::user();
        $kendaraans = $user->kendaraans;

        // Ambil ID Kendaraan yang difilter dari query string (?kendaraan_id=...)
        $selectedKendaraanId = $request->query('kendaraan_id');

        $riwayatQuery = Maintenance::whereIn('kendaraan_id', $kendaraans->pluck('id'))
                        ->with('kendaraan')
                        ->orderByDesc('tanggal');

        if (!empty($selectedKendaraanId)) {
            $riwayatQuery->where('kendaraan_id', $selectedKendaraanId);
        }

        $riwayat = $riwayatQuery->get();

        return view('kendaraan.riwayat', [
            'riwayat' => $riwayat,
            'kendaraans' => $kendaraans,
            'selectedKendaraanId' => $selectedKendaraanId,
        ]);
    }
}
