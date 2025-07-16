<?php

namespace App\Filament\Resources;

use App\Models\Kategori;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Tables;
use App\Filament\Resources\KategoriResource\Pages;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;
    protected static ?string $navigationGroup = 'Inventaris Barang';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Kategori';
    protected static ?string $pluralModelLabel = 'Kategori';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('kode_kategori')
                    ->label('Kode Kategori (3 Huruf)')
                    ->required()
                    ->minLength(3)
                    ->maxLength(3)
                    ->unique(ignoreRecord: true)
                    ->afterStateUpdated(fn (callable $set, $state) => $set('kode_kategori', strtoupper($state))),


                TextInput::make('nama_kategori')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(50),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_kategori')->label('Kode')->searchable(),
                TextColumn::make('nama_kategori')->label('Nama')->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageKategoris::route('/'),
        ];
    }
}
