<?php

// app/Http/Controllers/Device/MaintenanceController.php

namespace App\Http\Controllers\Device;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use Illuminate\Support\Facades\Storage;

class MaintenanceController extends Controller
{
    public function store(Request $request, $deviceId)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori_perawatan' => 'required|string',
            'deskripsi' => 'required|string',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('lampiran')) {
            $filePath = $request->file('lampiran')->store('bukti_perawatan', 'public');
        }

        Maintenance::create([
            'device_id' => $deviceId,
            'tanggal' => $request->tanggal,
            'status' => $request->kategori_perawatan,
            'deskripsi' => $request->deskripsi,
            'bukti' => $filePath,
        ]);

        return redirect()->route('device.riwayat', $deviceId)
                         ->with('success', 'Perawatan berhasil ditambahkan!');
    }
}
