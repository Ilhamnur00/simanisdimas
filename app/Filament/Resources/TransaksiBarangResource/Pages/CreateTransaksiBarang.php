<?php

namespace App\Filament\Resources\TransaksiBarangResource\Pages;

use App\Models\Barang;
use App\Models\DetailBarang;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\TransaksiBarangResource;
use Illuminate\Support\Facades\Auth;

class CreateTransaksiBarang extends CreateRecord
{
    protected static string $resource = TransaksiBarangResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $barang = Barang::findOrFail($data['barang_id']);

        // Hanya buat detail_barang saat transaksi masuk
        if ($data['jenis_transaksi'] === 'masuk') {
            // Ambil nilai TKDN hanya jika status_asal TKDN
            $nilaiTkdn = $data['status_asal'] === 'TKDN' ? ($data['nilai_tkdn'] ?? 0) : null;

            // Hitung total_harga
            $totalHarga = ($data['harga_satuan'] ?? 0) * $data['jumlah_barang'];

            // Cari apakah sudah ada detail_barang dengan kombinasi sama
            $detail = DetailBarang::where('barang_id', $barang->id)
                ->where('status_asal', $data['status_asal'])
                ->where('harga_satuan', $data['harga_satuan'])
                ->when($data['status_asal'] === 'TKDN', function ($query) use ($nilaiTkdn) {
                    return $query->where('nilai_tkdn', $nilaiTkdn);
                })
                ->first();

            // Jika belum ada → buat detail_barang
            if (!$detail) {
                $detail = DetailBarang::create([
                    'barang_id' => $barang->id,
                    'status_asal' => $data['status_asal'],
                    'nilai_tkdn' => $nilaiTkdn,
                    'harga_satuan' => $data['harga_satuan'],
                    'jumlah' => $data['jumlah_barang'],
                    'total_harga' => $totalHarga,
                ]);
            } else {
                // Jika sudah ada → update jumlah dan total_harga
                $detail->increment('jumlah', $data['jumlah_barang']);
                $detail->increment('total_harga', $totalHarga);
            }

            // Hubungkan transaksi dengan detail_barang
            $data['detail_barang_id'] = $detail->id;
            $data['total_harga'] = $totalHarga;
        }

        // Set data umum
        $data['user_id'] = Auth::id();
        $data['status'] = 'Disetujui'; // Transaksi admin langsung disetujui

        return $data;
    }
}
