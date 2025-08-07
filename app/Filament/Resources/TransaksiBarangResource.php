<?php

namespace App\Filament\Resources;

use App\Models\Barang;
use App\Models\DetailBarang;
use App\Models\TransaksiBarang;
use App\Filament\Resources\TransaksiBarangResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;
use Filament\Forms\Components\Placeholder;

class TransaksiBarangResource extends Resource
{
    protected static ?string $model = TransaksiBarang::class;
    protected static ?string $navigationGroup = 'Manajemen Barang';
    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationLabel = 'Transaksi';
    protected static ?string $pluralModelLabel = 'Transaksi';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('jenis_transaksi')
                ->label('Jenis Transaksi')
                ->options([
                    'masuk' => 'Masuk',
                    'keluar' => 'Keluar',
                ])
                ->live()
                ->required(),

            Forms\Components\Group::make([
            Select::make('barang_id')
                ->label('Nama Barang')
                ->options(fn () => Barang::pluck('nama_barang', 'id'))
                ->relationship('barang', 'nama_barang')
                ->searchable()
                ->live()
                ->afterStateUpdated(fn ($state, callable $set) => 
                    $set('stokBarang', Barang::find($state)?->stok ?? 0)
                )
                ->required()
                ->createOptionForm([
                    Forms\Components\TextInput::make('nama_barang')
                        ->label('Nama Barang')
                        ->required(),
                    Forms\Components\Select::make('kategori_id')
                        ->relationship('kategori', 'nama_kategori')
                        ->label('Kategori')
                        ->required(),
                ])
                ->createOptionAction(function (Action $action) {
                    return $action
                        ->label('Tambah Barang Baru')
                        ->modalHeading('Tambah Barang Baru')
                        ->modalSubmitActionLabel('Simpan')
                        ->visible(fn (Get $get) => $get('jenis_transaksi') === 'masuk');
                }),

            Placeholder::make('stok_info')
                ->label('')
                ->content(fn ($get) =>
                    $get('barang_id')
                        ? 'Stok tersedia: ' . $get('stokBarang') . ' unit'
                        : ''
                )
                ->extraAttributes(['class' => 'text-sm text-gray-500 -mt-2']),
            ])
            ->columnSpan(1),

            TextInput::make('jumlah_barang')
                ->label('Jumlah Barang')
                ->numeric()
                ->minValue(1)
                ->required(),

            TextInput::make('harga_satuan')
                ->label('Harga Satuan')
                ->numeric()
                ->minValue(0)
                ->required(fn (Forms\Get $get) => $get('jenis_transaksi') === 'masuk')
                ->hidden(fn (Forms\Get $get) => $get('jenis_transaksi') !== 'masuk'),

            Select::make('status_asal')
                ->label('Asal Pengadaan')
                ->options([
                    'TKDN' => 'TKDN',
                    'PDN' => 'PDN',
                    'IMPOR' => 'IMPOR',
                ])
                ->required(fn (Forms\Get $get) => $get('jenis_transaksi') === 'masuk')
                ->hidden(fn (Forms\Get $get) => $get('jenis_transaksi') !== 'masuk')
                ->live(),

            TextInput::make('nilai_tkdn')
                ->label('Nilai TKDN (%)')
                ->numeric()
                ->suffix('%')
                ->minValue(0)
                ->required(fn (Forms\Get $get) => $get('status_asal') === 'TKDN')
                ->hidden(fn (Forms\Get $get) => $get('status_asal') !== 'TKDN'),
            
            DatePicker::make('tanggal')
                ->label('Tanggal')
                ->required(),

            TextInput::make('total_harga')
                ->numeric()
                ->dehydrated()
                ->hidden()
                ->afterStateHydrated(function (TextInput $component, Forms\Get $get) {
                    $jumlah = $get('jumlah_barang');
                    $harga = $get('harga_satuan');
                    if ($jumlah && $harga) {
                        $component->state($jumlah * $harga);
                    }
                }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')
                ->label('No Transaksi')
                ->formatStateUsing(fn ($state) => 'TRX' . str_pad($state, 3, '0', STR_PAD_LEFT))
                ->sortable()
                ->searchable(),

            TextColumn::make('jenis_transaksi')
                ->label('Jenis')
                ->badge() // aktifkan tampilan badge
                ->color(fn (string $state): string => match ($state) {
                    'masuk' => 'success',
                    'keluar' => 'danger',
                    default => 'gray',
                })
                #->required()
                #->reactive()
                ->formatStateUsing(fn ($state) => ucfirst($state)),

            TextColumn::make('user.name')
                ->label('User')
                ->sortable()
                ->searchable()
                ->wrap(),

            TextColumn::make('barang.nama_barang')
                ->label('Barang')
                ->sortable()
                ->searchable()
                ->wrap(),

            TextColumn::make('jumlah_barang')
                ->label('Jumlah'),

            TextColumn::make('detailBarang.harga_satuan')
                ->label('Harga')
                ->formatStateUsing(fn ($state, $record) =>
                    $record->jenis_transaksi === 'masuk'
                        ? 'Rp. ' . number_format($state, 0, ',', '.')
                        : null
                ),

            TextColumn::make('detailBarang.total_harga')
                ->label('Total Harga')
                ->money('IDR', true)
                ->formatStateUsing(fn ($state, $record) =>
                    $record->jenis_transaksi === 'masuk'
                        ? 'Rp. ' . number_format($state, 0, ',', '.')
                        : null
                ),

            TextColumn::make('detailBarang.status_asal')
                ->label('Pengadaan')
                ->formatStateUsing(fn ($state, $record) => 
                    $record->jenis_transaksi === 'masuk' ? $state : null
                ),

            TextColumn::make('detailBarang.nilai_tkdn')
                ->label('Nilai TKDN')
                ->formatStateUsing(function ($state, $record) {
                    if ($record->jenis_transaksi === 'masuk' && $record->detailBarang->status_asal === 'TKDN') {
                        return $state !== null ? rtrim(rtrim(number_format($state, 2, '.', ''), '0'), '.') . '%' : '-';
                    }
                    return null;
                }),

            TextColumn::make('tanggal')
                ->label('Tanggal')
                ->date()
                ->date(format: 'd-m-Y'),
        ])

        ->filters([
            SelectFilter::make('jenis_transaksi')
                ->label('Jenis Transaksi')
                ->options([
                    'masuk' => 'Transaksi Masuk',
                    'keluar' => 'Transaksi Keluar',
                ]),
        ])

        ->contentGrid(['sm' => 1])
        ->paginated([10, 25, 50])
        ->striped()
        ->actions([])
        ->bulkActions([])
        ->recordUrl(null);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksiBarangs::route('/'),
            'create' => Pages\CreateTransaksiBarang::route('/create'),
        ];
    }
}