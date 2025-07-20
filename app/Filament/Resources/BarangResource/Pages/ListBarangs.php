<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\TransaksiBarangResource;


class ListBarangs extends ListRecords
{
    protected static string $resource = BarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Barang'),

            Actions\Action::make('transaksiBaru')
                ->label('Transaksi Baru')
                ->icon('heroicon-o-plus-circle')
                ->url(TransaksiBarangResource::getUrl('create'))
                ->color('info'),
        ];
    }

}
