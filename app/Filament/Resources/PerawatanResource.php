<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerawatanResource\Pages;
use App\Models\Maintenance;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class PerawatanResource extends Resource
{
    protected static ?string $model = Maintenance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Riwayat Perawatan';
    protected static ?string $navigationGroup = 'Manajemen Device';

    protected static ?string $label = 'Riwayat Perawatan';
    protected static ?string $pluralLabel = 'Riwayat Perawatan';


    public static function table(Table $table): Table
    {
        return $table
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
                            return '<span class="text-gray-400 italic">-</span>';
                        }

                        $url = asset('storage/' . $state);

                        return '<a href="' . $url . '" target="_blank" class="text-blue-600 hover:underline font-semibold">Lihat</a>';
                    })
                    ->html(),

                TextColumn::make('tanggal')
                    ->date()
                    ->label('Tanggal')
                    ->sortable(),
            ])
            ->defaultSort('tanggal', 'desc')
            ->filters([])
            ->actions([]); // Tidak menampilkan tombol edit/delete
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPerawatans::route('/'),
            'edit' => Pages\EditPerawatan::route('/{record}/edit'),
        ];
    }
}
