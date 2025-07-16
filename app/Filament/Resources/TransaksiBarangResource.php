<?php

namespace App\Filament\Resources;

use App\Models\TransaksiBarang;
use App\Models\Barang;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\TransaksiBarangResource\Pages;

class TransaksiBarangResource extends Resource
{
    protected static ?string $model = TransaksiBarang::class;

    protected static ?string $navigationGroup = 'Inventaris Barang';
    protected static ?string $navigationLabel = 'Transaksi Barang';
    protected static ?string $pluralModelLabel = 'Transaksi Barang';
    protected static ?int $navigationSort = 3;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Hidden::make('user_id')->default(fn () => Auth::id()),

            Select::make('jenis_transaksi')
                ->label('Jenis Transaksi')
                ->options([
                    'masuk' => 'Masuk (Pengadaan)',
                    'keluar' => 'Keluar (Permintaan)',
                ])
                ->required()
                ->reactive(),

            Select::make('barang_id')
                ->label('Barang')
                ->relationship('barang', 'nama_barang')
                ->searchable()
                ->required()
                ->createOptionForm([
                    TextInput::make('kode_barang')->label('Kode Barang')->required()->unique(ignoreRecord: true),
                    TextInput::make('nama_barang')->label('Nama Barang')->required(),
                    Select::make('kategori_id')
                        ->label('Kategori')
                        ->relationship('kategori', 'nama_kategori')
                        ->required(),
                    Select::make('status_asal')
                        ->label('Asal Barang')
                        ->options([
                            'TKDN' => 'TKDN',
                            'PDN' => 'PDN',
                            'IMPOR' => 'IMPOR',
                        ])
                        ->required()
                        ->reactive(),
                    TextInput::make('nilai_tkdn')
                        ->label('Nilai TKDN (%)')
                        ->numeric()
                        ->suffix('%')
                        ->visible(fn ($get) => $get('status_asal') === 'TKDN'),
                    TextInput::make('stok')
                        ->label('Stok Awal')
                        ->numeric()
                        ->default(0),
                    TextInput::make('harga_satuan')
                        ->label('Harga Satuan')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),
                ])
                ->createOptionUsing(fn (array $data) => Barang::create($data))
                ->reactive(),

            // Pilihan status_asal untuk setiap transaksi baru
            Select::make('status_asal')
                ->label('Status Pengadaan')
                ->options([
                    'TKDN' => 'TKDN',
                    'PDN' => 'PDN',
                    'IMPOR' => 'IMPOR',
                ])
                ->required()
                ->reactive(),

            TextInput::make('nilai_tkdn')
                ->label('Nilai TKDN (%)')
                ->numeric()
                ->suffix('%')
                ->visible(fn ($get) => $get('status_asal') === 'TKDN'),

            TextInput::make('jumlah_barang')
                ->label('Jumlah Barang')
                ->numeric()
                ->required()
                ->reactive()
                ->afterStateUpdated(fn ($set, $get) =>
                    $set('total_harga', (float) $get('jumlah_barang') * (float) $get('harga_satuan'))
                ),

            TextInput::make('harga_satuan')
                ->label('Harga Satuan')
                ->numeric()
                ->prefix('Rp')
                ->required(fn ($get) => $get('jenis_transaksi') === 'masuk')
                ->visible(fn ($get) => $get('jenis_transaksi') === 'masuk')
                ->reactive()
                ->afterStateUpdated(fn ($set, $get) =>
                    $set('total_harga', (float) $get('jumlah_barang') * (float) $get('harga_satuan'))
                ),

            TextInput::make('total_harga')
                ->label('Total Harga')
                ->prefix('Rp')
                ->disabled()
                ->dehydrated(false)
                ->visible(fn ($get) => $get('jenis_transaksi') === 'masuk')
                ->afterStateHydrated(fn ($set, $record) =>
                    $set('total_harga', $record?->total_harga ?? 0)
                ),

            Select::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                ])
                ->default('pending')
                ->required(),

            DatePicker::make('tanggal')->label('Tanggal Transaksi')->required(),

        ])->columns(2);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('barang.nama_barang')->label('Barang')->searchable(),
                TextColumn::make('barang.kategori.nama_kategori')->label('Kategori')->toggleable(),
                TextColumn::make('status_asal')->label('Asal')->badge()->toggleable(),
                TextColumn::make('nilai_tkdn')->label('TKDN')->suffix('%')->toggleable(),
                TextColumn::make('jenis_transaksi')->label('Jenis')->badge(),
                TextColumn::make('jumlah_barang')->label('Jumlah'),
                TextColumn::make('harga_satuan')->label('Harga')->money('IDR')->sortable()->toggleable(),
                TextColumn::make('total_harga')->label('Total')->money('IDR')->sortable()->toggleable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('tanggal')->label('Tanggal')->date('d M Y'),
                TextColumn::make('user.name')->label('Admin')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_transaksi')->options([
                    'masuk' => 'Masuk',
                    'keluar' => 'Keluar',
                ]),
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ]),
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
            'index' => Pages\ManageTransaksiBarangs::route('/'),
        ];
    }
}
