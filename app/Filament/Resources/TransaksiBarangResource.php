<?php

namespace App\Filament\Resources;

use App\Models\Barang;
use App\Models\Kategori;
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
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Illuminate\Database\QueryException;

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
                    ->default(fn () => request()->query('barang_id')) // auto-select jika datang dari CreateBarang
                    ->afterStateUpdated(fn ($state, callable $set) =>
                        $set('stokBarang', Barang::find($state)?->stok ?? 0)
                    )
                    ->required()
                    ->createOptionForm([
                        TextInput::make('nama_barang')
                            ->label('Nama Barang')
                            ->required(),
                        Select::make('kategori_id')
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
                    })
                    ->createOptionUsing(function (array $data) {
                        // Normalisasi nama untuk cek duplikat (case-insensitive)
                        $normalized = mb_strtolower(trim($data['nama_barang']));

                        if ($existing = Barang::whereRaw('LOWER(nama_barang) = ?', [$normalized])->first()) {
                            Notification::make()
                                ->title('Barang sudah ada')
                                ->body('Barang "' . $existing->nama_barang . '" sudah tersedia dan dipilih otomatis.')
                                ->warning()
                                ->send();

                            // return key supaya Select auto memilihnya
                            return $existing->getKey();
                        }

                        // Generate kode_barang (sesuaikan pola bila perlu)
                        $kategori = Kategori::find($data['kategori_id']);
                        $prefix   = $kategori?->kode_kategori ?? 'BRG';
                        $count    = Barang::where('kategori_id', $kategori?->id)->count() + 1;
                        $kode     = $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);

                        try {
                            $created = Barang::create([
                                'nama_barang' => trim($data['nama_barang']),
                                'kategori_id' => $data['kategori_id'],
                                'kode_barang' => $kode,
                            ]);

                            Notification::make()
                                ->title('Barang dibuat')
                                ->body('Barang berhasil ditambahkan dan dipilih otomatis.')
                                ->success()
                                ->send();

                            return $created->getKey();
                        } catch (QueryException $e) {
                            // Fallback jika race condition (duplicate key 1062)
                            if (($e->errorInfo[1] ?? null) == 1062) {
                                if ($existing = Barang::whereRaw('LOWER(nama_barang) = ?', [$normalized])->first()) {
                                    Notification::make()
                                        ->title('Barang sudah ada')
                                        ->body('Barang "' . $existing->nama_barang . '" sudah tersedia dan dipilih otomatis.')
                                        ->warning()
                                        ->send();

                                    return $existing->getKey();
                                }
                            }
                            throw $e;
                        }
                    }),

                Placeholder::make('stok_info')
                    ->label('')
                    ->content(fn ($get) =>
                        $get('barang_id')
                            ? 'Stok tersedia: ' . ($get('stokBarang') ?? 0) . ' unit'
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
                ->required(fn (Get $get) => $get('jenis_transaksi') === 'masuk')
                ->hidden(fn (Get $get) => $get('jenis_transaksi') !== 'masuk'),

            Select::make('status_asal')
                ->label('Asal Pengadaan')
                ->options([
                    'TKDN' => 'TKDN',
                    'PDN'  => 'PDN',
                    'IMPOR'=> 'IMPOR',
                ])
                ->required(fn (Get $get) => $get('jenis_transaksi') === 'masuk')
                ->hidden(fn (Get $get) => $get('jenis_transaksi') !== 'masuk')
                ->live(),

            TextInput::make('nilai_tkdn')
                ->label('Nilai TKDN (%)')
                ->numeric()
                ->suffix('%')
                ->minValue(0)
                ->required(fn (Get $get) => $get('status_asal') === 'TKDN')
                ->hidden(fn (Get $get) => $get('status_asal') !== 'TKDN'),

            DatePicker::make('tanggal')
                ->label('Tanggal')
                ->required(),

            TextInput::make('total_harga')
                ->numeric()
                ->dehydrated()
                ->hidden()
                ->afterStateHydrated(function (TextInput $component, Get $get) {
                    $jumlah = $get('jumlah_barang');
                    $harga  = $get('harga_satuan');
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
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'masuk'  => 'success',
                    'keluar' => 'danger',
                    default  => 'gray',
                })
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
                    if (
                        $record->jenis_transaksi === 'masuk'
                        && ($record->detailBarang?->status_asal === 'TKDN')
                    ) {
                        return $state !== null
                            ? rtrim(rtrim(number_format($state, 2, '.', ''), '0'), '.') . '%'
                            : '-';
                    }
                    return null;
                }),

            TextColumn::make('tanggal')
                ->label('Tanggal')
                ->date('d-m-Y'),
        ])
            ->filters([
                SelectFilter::make('jenis_transaksi')
                    ->label('Jenis Transaksi')
                    ->options([
                        'masuk'  => 'Transaksi Masuk',
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
            'index'  => Pages\ListTransaksiBarangs::route('/'),
            'create' => Pages\CreateTransaksiBarang::route('/create'),
        ];
    }
}
