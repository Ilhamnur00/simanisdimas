<?php

namespace App\Filament\Resources\TransaksiBarangResource\Pages;

use App\Filament\Resources\TransaksiBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTransaksiBarangs extends ManageRecords
{
    protected static string $resource = TransaksiBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
