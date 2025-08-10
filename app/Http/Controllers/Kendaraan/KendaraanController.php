<?php

namespace App\Http\Controllers\Kendaraan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kendaraan;
use App\Models\LaporanPajak;
use App\Models\LaporanPerawatan;
use Carbon\Carbon;
use App\Notifications\PajakReminderNotification;
use Illuminate\Support\Facades\Notification;

class KendaraanController extends Controller
{
    public function index()
    {
        $kendaraans = Kendaraan::with('user')->where('user_id', Auth::id())->get();
        return view('kendaraan.index', compact('kendaraans'));
    }

    public function formLaporanPerawatan()
    {
        $kendaraans = Kendaraan::where('user_id', Auth::id())
            ->with('user')
            ->get()
            ->map(function ($k) {
                $k->nama_user = $k->user->name ?? '-';
                return $k;
            });

        return view('kendaraan.laporan-perawatan', compact('kendaraans'));
    }


    public function laporPajak()
    {
        $kendaraans = Kendaraan::where('user_id', Auth::id())
            ->with('user')
            ->get()
            ->map(function ($k) {
                $k->nama_user = $k->user->name ?? '-';
                return $k;
            });

        return view('kendaraan.lapor-pajak', compact('kendaraans'));
    }

    public function storeLaporPajak(Request $request)
    {
        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'jenis_pajak' => 'required|string',
            'tanggal' => 'required|date',
            'deskripsi' => 'nullable|string',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $filePath = $request->hasFile('bukti')
                ? $request->file('bukti')->store('pajak', 'public')
                : null;

            $laporan = LaporanPajak::create([
                'kendaraan_id' => $request->kendaraan_id,
                'jenis_pajak' => $request->jenis_pajak,
                'tanggal' => $request->tanggal,
                'deskripsi' => $request->deskripsi,
                'bukti' => $filePath,
            ]);

            // Ambil kendaraan
            $kendaraan = Kendaraan::find($request->kendaraan_id);

            if ($kendaraan && $kendaraan->tanggal_pajak) {
                $tanggalPajakSaatIni = Carbon::parse($kendaraan->tanggal_pajak);
                $tanggalLaporan = Carbon::parse($request->tanggal);

                // Update jika laporan dilakukan di tahun yang sama dengan tanggal pajak
                if ($tanggalLaporan->year === $tanggalPajakSaatIni->year) {
                    $kendaraan->tanggal_pajak = $tanggalPajakSaatIni->copy()->addYear();

                    // Reset flag reminder
                    $kendaraan->reminder_h7_sent = false;
                    $kendaraan->reminder_h0_sent = false;
                    $kendaraan->save();

                    // Kirim notifikasi ke email user
                    if ($kendaraan->user && $kendaraan->user->email) {
                        $kendaraan->user->notify(new PajakReminderNotification(
                            $kendaraan,
                            'Pemberitahuan Pajak Kendaraan Baru'
                        ));
                    }
                }
            }

            return redirect()->route('kendaraan.riwayat')
                ->with('success', 'Laporan pajak berhasil disimpan, jatuh tempo diperbarui, dan notifikasi dikirim.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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

    protected function authorizeAccess(Kendaraan $kendaraan): void
    {
        if ($kendaraan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke kendaraan ini.');
        }
    }
}
