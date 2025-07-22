<?php

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaintenanceController extends Controller
{
    /**
     * Simpan laporan perawatan baru.
     */
    public function store(Request $request, $deviceId)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori_perawatan' => 'required|string',
            'deskripsi' => 'required|string',
            'bukti' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('bukti')) {
            $path = $request->file('bukti')->store('bukti_perawatan', 'public');
        }

        Maintenance::create([
            'device_id' => $deviceId,
            'tanggal' => $request->tanggal,
            'kategori_perawatan' => $request->kategori_perawatan,
            'deskripsi' => $request->deskripsi,
            'bukti' => $path,
        ]);

        return redirect()->route('device.riwayatAll')
                 ->with('success', 'Laporan perawatan berhasil disimpan.');

    }

    /**
     * Tampilkan riwayat semua perawatan milik user, dengan filter device opsional.
     */
    public function riwayatAll(Request $request)
    {
        $user = Auth::user();
        $devices = $user->devices;

        // Ambil ID device yang difilter dari query string (?device_id=...)
        $selectedDeviceId = $request->query('device_id');

        $riwayatQuery = Maintenance::whereIn('device_id', $devices->pluck('id'))
                        ->with('device')
                        ->orderByDesc('tanggal');

        if (!empty($selectedDeviceId)) {
            $riwayatQuery->where('device_id', $selectedDeviceId);
        }

        $riwayat = $riwayatQuery->get();

        return view('device.riwayat', [
            'riwayat' => $riwayat,
            'devices' => $devices,
            'selectedDeviceId' => $selectedDeviceId,
        ]);
    }
}
