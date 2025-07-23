<?php

namespace App\Http\Controllers\Kendaraan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kendaraan;
use Illuminate\Support\Facades\Auth;

class KendaraanController extends Controller
{
    /**
     * Menampilkan daftar semua perangkat milik user yang login
     */
    public function index()
    {
        $kendaraans = Kendaraan::where('user_id', Auth::id())->get();

        return view('kendaraan.index', compact('kendaraans'));
    }

    /**
     * Menampilkan detail dan form laporan perawatan untuk 1 perangkat
     */
    public function show(Kendaraan $kendaraan)
    {
        // Batasi agar hanya user pemilik yang bisa melihat detail perangkat
        if ($kendaraan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke perangkat ini.');
        }

        return view('kendaraan.show', compact('kendaraan'));
    }

    /**
     * Menampilkan riwayat perawatan untuk 1 perangkat
     */
    public function riwayat($id)
    {
        $kendaraan = Kendaraan::with(['maintenances.kendaraan.user'])->findOrFail($id);

        // Batasi akses hanya untuk pemilik perangkat
        if ($kendaraan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke perangkat ini.');
        }

        // Ambil semua kendaraan milik user untuk dropdown di view
        $kendaraans = Kendaraan::where('user_id', Auth::id())->get();

        $riwayat = $kendaraan->maintenances()->with('kendaraan.user')->latest()->get();

        return view('kendaraan.riwayat', [
            'kendaraan' => $kendaraan,
            'riwayat' => $riwayat,
            'kendaraans' => $kendaraans,
        ]);
    }
}
