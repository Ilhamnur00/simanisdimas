<?php

namespace App\Http\Controllers\Kendaraan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kendaraan;
use Illuminate\Support\Facades\Auth;

class KendaraanController extends Controller
{
    /**
     * Menampilkan daftar semua kendaraan milik user yang login.
     */
    public function index()
    {
        // Ambil kendaraan milik user saat ini beserta relasi user-nya
        $kendaraans = Kendaraan::with('user')
            ->where('user_id', Auth::id())
            ->get();

        return view('kendaraan.index', compact('kendaraans'));
    }

    /**
     * Menampilkan detail dan form laporan perawatan untuk satu kendaraan.
     *
     * @param  Kendaraan  $kendaraan
     * @return \Illuminate\View\View
     */
    public function show(Kendaraan $kendaraan)
    {
        // Pastikan hanya pemilik kendaraan yang bisa melihat detailnya
        if ($kendaraan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke kendaraan ini.');
        }

        return view('kendaraan.show', compact('kendaraan'));
    }

    /**
     * Menampilkan riwayat perawatan untuk satu kendaraan.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function riwayat($id)
    {
        // Ambil kendaraan beserta riwayat maintenancenya
        $kendaraan = Kendaraan::with(['maintenances.kendaraan.user'])->findOrFail($id);

        // Batasi akses hanya untuk pemilik
        if ($kendaraan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke kendaraan ini.');
        }

        // Ambil semua kendaraan milik user untuk dropdown jika dibutuhkan
        $kendaraans = Kendaraan::where('user_id', Auth::id())->get();

        // Ambil riwayat perawatan kendaraan saat ini
        $riwayat = $kendaraan->maintenances()
                             ->with('kendaraan.user')
                             ->latest()
                             ->get();

        return view('kendaraan.riwayat', [
            'kendaraan' => $kendaraan,
            'riwayat' => $riwayat,
            'kendaraans' => $kendaraans,
        ]);
    }
}
