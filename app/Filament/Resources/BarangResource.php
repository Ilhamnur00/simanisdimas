<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Models\Barang;
use App\Models\Kategori;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{TextInput, Select, Hidden};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;
    protected static ?string $navigationGroup = 'Inventaris Barang' ;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel= 'Barang';
    protected static ?string $pluralModelLabel = 'Daftar Barang';
    protected static ?int $NavigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('kategori_id')
                ->label('Kategori')
                ->relationship('kategori', 'nama_kategori')
                ->required()
                ->reactive()
                ->disabledOn('edit'),

            TextInput::make('nama_barang')
                ->label('Nama Barang')
                ->required()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $kategori = Kategori::find($get('kategori_id'));
                    if ($kategori) {
                        $prefix = $kategori->kode_kategori;
                        $count = Barang::where('kategori_id', $kategori->id)->count() + 1;
                        $kodeBarang = $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
                        $set('kode_barang', $kodeBarang);
                    }
                }),

            TextInput::make('kode_barang')
                ->label('Kode Barang')
                ->required()
                ->readOnly()
                ->disabledOn('edit'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('kode_barang')->searchable(),
            TextColumn::make('nama_barang')
                ->label('Nama Barang')
                ->searchable()
                ->url(fn (Barang $record) => static::getUrl('edit', ['record' => $record])),
            TextColumn::make('kategori.nama_kategori')->label('Kategori'),
            TextColumn::make('stok')
                ->label('Stok Tersedia'),
        ])->actions([
            Tables\Actions\Action::make('Lihat Detail')
                ->icon('heroicon-s-document-magnifying-glass')
                ->label('Rincian')
                ->url(fn (Barang $record) => static::getUrl('rincian', ['record' => $record]))
                ->openUrlInNewTab(false),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
            'rincian' => Pages\RincianBarang::route('/{record}'),
        ];
    }
}
