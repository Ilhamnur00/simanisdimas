<?php

namespace App\Filament\Resources\PengajuanBarangResource\Pages;

use App\Filament\Resources\PengajuanBarangResource;
use Filament\Resources\Pages\ListRecords;

class ListPengajuanBarangs extends ListRecords
{
    protected static string $resource = PengajuanBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
