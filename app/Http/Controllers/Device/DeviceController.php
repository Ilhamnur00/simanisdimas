<?php

namespace App\Http\Controllers\Device;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Device;

class DeviceController extends Controller
{
    /**
     * Menampilkan daftar semua perangkat
     */
    public function index()
    {
        $devices = Device::all();
        return view('device.index', compact('devices'));
    }

    /**
     * Menampilkan detail dan form laporan perawatan untuk 1 perangkat
     */
    public function show(Device $device)
    {
        return view('device.show', compact('device'));
    }

    /**
     * Menampilkan riwayat perawatan untuk 1 perangkat
     */
    public function riwayat($id)
    {
        $device = Device::with(['maintenances.device.user'])->findOrFail($id);
        $riwayat = $device->maintenances()->with('device.user')->latest()->get();

        return view('device.riwayat', compact('device', 'riwayat'));
    }
}
