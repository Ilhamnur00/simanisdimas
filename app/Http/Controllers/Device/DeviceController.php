<?php

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    /**
     * Menampilkan daftar semua perangkat milik user yang login
     */
    public function index()
    {
        $devices = Device::where('user_id', Auth::id())->get();

        return view('device.index', compact('devices'));
    }

    /**
     * Menampilkan detail dan form laporan perawatan untuk 1 perangkat
     */
    public function show(Device $device)
    {
        // Batasi agar hanya user pemilik yang bisa melihat detail perangkat
        if ($device->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke perangkat ini.');
        }

        return view('device.show', compact('device'));
    }

    /**
     * Menampilkan riwayat perawatan untuk 1 perangkat
     */
    public function riwayat($id)
    {
        $device = Device::with(['maintenances.device.user'])->findOrFail($id);

        // Batasi akses hanya untuk pemilik perangkat
        if ($device->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke perangkat ini.');
        }

        $riwayat = $device->maintenances()->with('device.user')->latest()->get();

        return view('device.riwayat', compact('device', 'riwayat'));
    }
}
