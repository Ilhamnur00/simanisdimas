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
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

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

            Hidden::make('stok')->default(0),
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
                ->label('Stok Tersedia')
                ->getStateUsing(function ($record) {
                    $masuk = $record->transaksiBarang()
                        ->where('jenis_transaksi', 'masuk')
                        ->where('status', 'Disetujui')
                        ->sum('jumlah_barang');

                    $keluar = $record->transaksiBarang()
                        ->where('jenis_transaksi', 'keluar')
                        ->where('status', 'Disetujui')
                        ->sum('jumlah_barang');

                    return $masuk - $keluar;
                }),
        ])->actions([
            Tables\Actions\Action::make('Lihat Detail')
                ->icon('heroicon-m-eye')
                ->label('Lihat Detail')
                ->url(fn (Barang $record) => static::getUrl('view', ['record' => $record]))
                ->openUrlInNewTab(false),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
            'view' => Pages\ViewBarang::route('/{record}'),
        ];
    }
}
