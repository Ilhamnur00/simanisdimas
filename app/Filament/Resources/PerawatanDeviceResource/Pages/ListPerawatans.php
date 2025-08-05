<?php

namespace App\Filament\Resources\PerawatanDeviceResource\Pages;

use App\Filament\Resources\PerawatanDeviceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPerawatans extends ListRecords
{
    protected static string $resource = PerawatanDeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Riwayat Perawatan';
    }
}
