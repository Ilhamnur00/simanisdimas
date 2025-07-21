<?php

namespace App\Filament\Resources\TransaksiBarangResource\Pages;

use App\Models\Barang;
use App\Models\DetailBarang;
use App\Models\TransaksiBarang;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\TransaksiBarangResource;
use Illuminate\Support\Facades\Auth;

class CreateTransaksiBarang extends CreateRecord
{
    protected static string $resource = TransaksiBarangResource::class;
        
    public function getTitle(): string
    {
        return 'Transaksi Barang';
    }

    public function getBreadcrumb(): string
    {
        return 'Transaksi Baru';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $barang = Barang::findOrFail($data['barang_id']);
        $data['user_id'] = Auth::id();
        $data['status'] = 'Disetujui';

        if ($data['jenis_transaksi'] === 'masuk') {
            $nilaiTkdn = $data['status_asal'] === 'TKDN' ? ($data['nilai_tkdn'] ?? 0) : null;
            $totalHarga = ($data['harga_satuan'] ?? 0) * $data['jumlah_barang'];

            // Cek apakah sudah ada detail barang yang sesuai
            $detail = DetailBarang::where('barang_id', $barang->id)
                ->where('status_asal', $data['status_asal'])
                ->where('harga_satuan', $data['harga_satuan'])
                ->when($data['status_asal'] === 'TKDN', function ($query) use ($nilaiTkdn) {
                    return $query->where('nilai_tkdn', $nilaiTkdn);
                })
                ->first();

            // Jika belum ada, buat baru
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
                // Jika sudah ada, update jumlah dan total harga
                $detail->increment('jumlah', $data['jumlah_barang']);
                $detail->increment('total_harga', $totalHarga);
            }

            $data['detail_barang_id'] = $detail->id;
            $data['harga_satuan'] = $data['harga_satuan'];
            $data['status_asal'] = $data['status_asal'];
            $data['nilai_tkdn'] = $nilaiTkdn;
            $data['total_harga'] = $totalHarga;

        } elseif ($data['jenis_transaksi'] === 'keluar') {
            // Cek stok cukup
            if ($barang->stok < $data['jumlah_barang']) {
                throw new \Exception('Stok barang tidak mencukupi untuk transaksi keluar.');
            }

            // Ambil detail barang pertama yang ditemukan
            $detail = DetailBarang::where('barang_id', $barang->id)->first();
            if (!$detail) {
                throw new \Exception('Tidak ditemukan detail barang untuk barang ini.');
            }

            // Kurangi stok per detail (FIFO)
            $barang->keluarkanStok($data['jumlah_barang']);

            $data['detail_barang_id'] = $detail->id;
            $data['harga_satuan'] = null;
            $data['status_asal'] = null;
            $data['nilai_tkdn'] = null;
            $data['total_harga'] = null;
        }

        return $data;
    }
}
