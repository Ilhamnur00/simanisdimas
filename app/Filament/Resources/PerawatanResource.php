<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerawatanResource\Pages;
use App\Models\Maintenance;
use App\Models\Perawatan;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;

class PerawatanResource extends Resource
{
    protected static ?string $model = Maintenance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Riwayat Perawatan';
    protected static ?string $navigationGroup = 'Manajemen Device';

    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('device.nama')
                    ->label('Nama Device')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kategori_perawatan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->limit(30)
                    ->wrap(),
                Tables\Columns\ImageColumn::make('bukti')
                    ->label('Bukti')
                    ->height(60)
                    ->width(60),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->label('Tanggal')
                    ->sortable(),
            ])
            ->defaultSort('tanggal', 'desc')
            ->filters([])
            ->actions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPerawatans::route('/'),
            'edit' => Pages\EditPerawatan::route('/{record}/edit'),
        ];
    }
}
