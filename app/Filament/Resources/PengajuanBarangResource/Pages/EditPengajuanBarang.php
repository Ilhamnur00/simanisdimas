<?php

namespace App\Filament\Resources\PengajuanBarangResource\Pages;

use App\Filament\Resources\PengajuanBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengajuanBarang extends EditRecord
{
    protected static string $resource = PengajuanBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
