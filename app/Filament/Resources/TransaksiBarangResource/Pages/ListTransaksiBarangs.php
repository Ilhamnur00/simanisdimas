<?php

namespace App\Filament\Resources\TransaksiBarangResource\Pages;

use App\Filament\Resources\TransaksiBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransaksiBarangs extends ListRecords
{
    protected static string $resource = TransaksiBarangResource::class;
        
    public function getTitle(): string
    {
        return 'Riwayat Transaksi Barang';
    }

}
