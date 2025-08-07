<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriResource\Pages;
use App\Models\Kategori;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;

class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Manajemen Barang';
    protected static ?string $navigationLabel = 'Kategori';
    protected static ?string $pluralModelLabel = 'Daftar Kategori';
    protected static ?int $NavigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([

           Forms\Components\TextInput::make('kode_kategori')
                ->label('Kode Kategori')
                ->maxLength(3)
                ->required()
                ->rules(['alpha', 'size:3'])
                ->unique(ignoreRecord: true)
                ->disabled(fn (?Model $record) => $record !== null),

            Forms\Components\TextInput::make('nama_kategori')
                ->label('Nama Kategori')
                ->required()
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_kategori')
                    ->label('Kode')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_kategori')
                    ->label('Nama Kategori')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('barang_count')
                    ->label('Jumlah Barang')
                    ->counts('barang')
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function canDelete(Model $record): bool
    {
        return $record->barang()->count() === 0;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKategoris::route('/'),
            'create' => Pages\CreateKategori::route('/create'),
            'edit' => Pages\EditKategori::route('/{record}/edit'),
        ];
    }
}
