<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PajakKendaraanController extends Controller
{
    public function index()
    {
        $data = session('transaksi_kendaraan', []);
        return view('transaksi.kendaraan', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_kendaraan' => 'required',
            'nama_kendaraan' => 'required',
            'nama_pengguna' => 'required',
            'pajak' => 'required|date',
            'perawatan' => 'required',
            'tanggal_pengajuan' => 'required|date',
        ]);

        $status_pajak = now()->lt($request->pajak) ? 'Aktif' : 'Tidak Aktif';

        $data = session('transaksi_kendaraan', []);
        $data[] = [
            'id' => count($data) + 1,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'nama_kendaraan' => $request->nama_kendaraan,
            'nama_pengguna' => $request->nama_pengguna,
            'pajak' => $request->pajak,
            'status_pajak' => $status_pajak,
            'perawatan' => $request->perawatan,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
        ];

        session(['transaksi_kendaraan' => $data]);

        return redirect()->route('transaksi.kendaraan')->with('success', 'Data berhasil ditambahkan!');
    }
}
