<?php

namespace App\Http\Controllers\Kendaraan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kendaraan;
use App\Models\LaporanPajak;
use App\Models\LaporanPerawatan;

class KendaraanController extends Controller
{
    public function index()
    {
        $kendaraans = Kendaraan::with('user')->where('user_id', Auth::id())->get();
        return view('kendaraan.index', compact('kendaraans'));
    }

    public function formLaporanPerawatan()
    {
        $kendaraans = Kendaraan::where('user_id', Auth::id())->get();
        return view('kendaraan.laporan-perawatan', compact('kendaraans'));
    }

    public function laporPajak()
    {
        $kendaraans = Kendaraan::where('user_id', Auth::id())->get();
        return view('kendaraan.lapor-pajak', compact('kendaraans'));
    }

    public function storeLaporanPerawatan(Request $request)
    {
        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'tanggal' => 'required|date',
            'kategori_perawatan' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            $filePath = $request->hasFile('bukti')
                ? $request->file('bukti')->store('perawatan', 'public')
                : null;

            LaporanPerawatan::create([
                'kendaraan_id' => $request->kendaraan_id,
                'tanggal' => $request->tanggal,
                'kategori_perawatan' => $request->kategori_perawatan,
                'deskripsi' => $request->deskripsi,
                'bukti' => $filePath,
            ]);

            return redirect()->route('kendaraan.riwayat')->with('success', 'Laporan perawatan berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan laporan perawatan.');
        }
    }

    public function storeLaporPajak(Request $request)
    {
        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'jenis_pajak' => 'required|string',
            'tanggal' => 'required|date',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            LaporanPajak::create([
                'kendaraan_id' => $request->kendaraan_id,
                'jenis_pajak' => $request->jenis_pajak,
                'tanggal' => $request->tanggal,
                'deskripsi' => $request->deskripsi,
            ]);

            return redirect()->route('kendaraan.riwayat')->with('success', 'Laporan pajak berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan laporan pajak.');
        }
    }

    public function riwayat()
    {
        $kendaraans = Kendaraan::where('user_id', Auth::id())->get();
        $kendaraanIds = $kendaraans->pluck('id');

        $riwayatPerawatan = LaporanPerawatan::with('kendaraan')
            ->whereIn('kendaraan_id', $kendaraanIds)
            ->latest()
            ->get();

        $riwayatPajak = LaporanPajak::with('kendaraan')
            ->whereIn('kendaraan_id', $kendaraanIds)
            ->latest()
            ->get();

        return view('kendaraan.riwayat', compact('kendaraans', 'riwayatPerawatan', 'riwayatPajak'));
    }

    public function getRiwayatData(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:pajak,perawatan',
            'kendaraan_id' => 'required|exists:kendaraans,id',
        ]);

        $kendaraan = Kendaraan::where('id', $request->kendaraan_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $data = match ($request->jenis) {
            'pajak' => LaporanPajak::where('kendaraan_id', $kendaraan->id)->latest()->get(),
            'perawatan' => LaporanPerawatan::where('kendaraan_id', $kendaraan->id)->latest()->get(),
        };

        return response()->json($data);
    }

    public function show(Kendaraan $kendaraan)
    {
        $this->authorizeAccess($kendaraan);
        return view('kendaraan.show', compact('kendaraan'));
    }

    protected function authorizeAccess(Kendaraan $kendaraan): void
    {
        if ($kendaraan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke kendaraan ini.');
        }
    }
}
