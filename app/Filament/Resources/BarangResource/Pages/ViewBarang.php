<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use App\Filament\Resources\BarangResource\Widgets\RiwayatTransaksiTable;
use Filament\Resources\Pages\ViewRecord;

class ViewBarang extends ViewRecord
{
    protected static string $resource = BarangResource::class;

    protected function getFooterWidgets(): array
    {
        return [RiwayatTransaksiTable::class];
    }

}
