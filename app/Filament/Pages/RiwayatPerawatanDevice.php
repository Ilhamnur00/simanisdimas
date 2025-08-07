<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Models\Maintenance;

class RiwayatPerawatanDevice extends Page implements HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Riwayat Perawatan';
    protected static ?string $navigationGroup = 'Manajemen Device';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.riwayat-perawatan-device';

    public function table(Table $table): Table
    {
        return $table
            ->query(Maintenance::query())
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama User')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('device.nama')
                    ->label('Nama Device')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kategori_perawatan')
                    ->sortable(),

                TextColumn::make('deskripsi')
                    ->limit(30)
                    ->wrap(),

                TextColumn::make('bukti')
                    ->label('Bukti')
                    ->formatStateUsing(function ($state) {
                        if (!$state) {
                            return '<span class="text-blue-700 italic">-</span>';
                        }

                        $url = asset('storage/' . $state);

                        return '<a href="' . $url . '" target="_blank" class="text-blue-600 hover:underline font-semibold">Lihat</a>';
                    })
                    ->html(),

                TextColumn::make('tanggal')
                    ->date()
                    ->label('Tanggal')
                    ->sortable()
                    ->date(format: 'd-m-Y'),
            ])
            ->defaultSort('tanggal', 'desc')
            ->filters([])
            ->actions([]);
    }
}