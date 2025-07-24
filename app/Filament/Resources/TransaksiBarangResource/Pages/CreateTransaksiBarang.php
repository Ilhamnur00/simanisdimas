<?php

namespace App\Filament\Resources\TransaksiBarangResource\Pages;

use App\Models\Barang;
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

        // Validasi stok hanya jika transaksi keluar
        if ($data['jenis_transaksi'] === 'keluar' && $barang->stok < $data['jumlah_barang']) {
            throw new \Exception('Stok barang tidak mencukupi untuk transaksi keluar.');
        }

        // Isi data dasar
        $data['user_id'] = Auth::id();

        // Field lain seperti detail_barang_id, harga_satuan, dll akan diisi oleh Observer
        return $data;
    }
}
