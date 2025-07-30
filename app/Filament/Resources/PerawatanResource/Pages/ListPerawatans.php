<?php

namespace App\Filament\Resources\PerawatanResource\Pages;

use App\Filament\Resources\PerawatanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPerawatans extends ListRecords
{
    protected static string $resource = PerawatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
