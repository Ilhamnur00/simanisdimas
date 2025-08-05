<?php

namespace App\Filament\Resources\PerawatanDeviceResource\Pages;

use App\Filament\Resources\PerawatanDeviceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPerawatan extends EditRecord
{
    protected static string $resource = PerawatanDeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
