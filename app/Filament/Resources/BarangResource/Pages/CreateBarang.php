<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBarang extends CreateRecord
{
    protected static string $resource = BarangResource::class;

    // Hapus tombol "create & create another"
    protected function getCreateFormActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // Setelah berhasil create, redirect ke index (bukan stay di form)
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
