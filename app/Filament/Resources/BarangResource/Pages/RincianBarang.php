<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use App\Filament\Resources\BarangResource\Widgets\RiwayatTransaksiTable;
use Filament\Resources\Pages\ViewRecord;

class RincianBarang extends ViewRecord
{
    protected static string $resource = BarangResource::class;
    protected static ?string $pluralModelLabel = 'Rincian Barang';

    protected function getFooterWidgets(): array
    {
        return [RiwayatTransaksiTable::class];
    }
    
    public function getTitle(): string
    {
        return 'Rincian Barang';
    }

    public function getBreadcrumb(): string
    {
        return 'Rincian';
    }


}
