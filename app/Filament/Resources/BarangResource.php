<?php

namespace App\Filament\Resources;

use App\Models\Barang;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Tables;
use App\Filament\Resources\BarangResource\Pages;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;
    protected static ?string $navigationGroup = 'Inventaris Barang';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Daftar Barang';
    protected static ?string $pluralModelLabel = 'Barang';


    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            TextInput::make('kode_barang')
                ->label('Kode Barang')
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('nama_barang')
                ->label('Nama Barang')
                ->required(),

            Select::make('kategori_id')
                ->label('Kategori')
                ->relationship('kategori', 'nama_kategori')
                ->searchable()
                ->required(),

            Select::make('status_asal')
                ->label('Asal Barang')
                ->options([
                    'TKDN' => 'TKDN',
                    'PDN' => 'PDN',
                    'IMPOR' => 'IMPOR',
                ])
                ->reactive()
                ->required(),

            TextInput::make('nilai_tkdn')
                ->label('Nilai TKDN (%)')
                ->numeric()
                ->suffix('%')
                ->visible(fn ($get) => $get('status_asal') === 'TKDN'),

            TextInput::make('stok')
                ->label('Stok')
                ->numeric()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $harga = $get('harga_satuan');
                    if ($harga !== null) {
                        $set('total_harga', $state * $harga);
                    }
                }),

            TextInput::make('harga_satuan')
                ->label('Harga Satuan')
                ->numeric()
                ->prefix('Rp')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $stok = $get('stok');
                    if ($stok !== null) {
                        $set('total_harga', $stok * $state);
                    }
                }),

            TextInput::make('total_harga')
                ->label('Total Harga')
                ->numeric()
                ->prefix('Rp')
                ->disabled()
                ->dehydrated(false),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_barang')->label('Kode')->searchable(),
                TextColumn::make('nama_barang')->label('Nama')->searchable(),
                TextColumn::make('kategori.nama_kategori')->label('Kategori'),

                TextColumn::make('stok')
                    ->label('Stok')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 5 => 'danger',
                        $state <= 10 => 'warning',
                        default => 'success',
                    }),

                TextColumn::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->money('IDR', true),

                TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->money('IDR', true),

                TextColumn::make('status_asal')
                    ->label('Status Asal')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'TKDN' => 'success',
                        'PDN' => 'warning',
                        'IMPOR' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('nilai_tkdn')
                    ->label('Nilai TKDN (%)')
                    ->suffix('%')
                    ->formatStateUsing(fn ($state, $record) => $record->status_asal === 'TKDN' ? $state : '-'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama_kategori'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageBarangs::route('/'),
        ];
    }
}
