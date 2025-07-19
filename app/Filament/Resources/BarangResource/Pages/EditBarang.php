<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditBarang extends EditRecord
{
    protected static string $resource = BarangResource::class;

    protected function afterSave(): void
    {
        Notification::make()
            ->title('Berhasil')
            ->body('Nama barang berhasil diperbarui.')
            ->success()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl();
    }
}
