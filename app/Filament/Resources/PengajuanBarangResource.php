<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanBarangResource\Pages;
use App\Models\PengajuanBarang;
use App\Models\DetailBarang;
use App\Models\TransaksiBarang;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class PengajuanBarangResource extends Resource
{
    protected static ?string $model = PengajuanBarang::class;

    protected static ?string $navigationIcon = null;
    protected static ?string $navigationGroup = 'Inventaris Barang';
    protected static ?string $modelLabel = 'Pengajuan Barang';
    protected static ?string $pluralModelLabel = 'Pengajuan Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->disabled(),

                Select::make('barang_id')
                    ->relationship('barang', 'nama_barang')
                    ->disabled(),

                TextInput::make('jumlah_barang')->disabled(),
                TextInput::make('status')->disabled(),
                TextInput::make('keterangan')->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->where('status', 'Menunggu'))
            ->columns([
                TextColumn::make('tanggal')->label('Tanggal')->date(),
                TextColumn::make('user.name')->label('User'),
                TextColumn::make('barang.nama_barang')->label('Barang'),
                TextColumn::make('jumlah_barang')->label('Jumlah'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Actions\Action::make('setuju')
                    ->label('Setuju')
                    ->color('success')
                    ->icon('heroicon-m-check')
                    ->visible(fn ($record) => $record->status === 'Menunggu')
                    ->requiresConfirmation()
                    ->action(function (PengajuanBarang $record) {
                        // Update status pengajuan
                        $record->update(['status' => 'Disetujui']);

                        $detail = DetailBarang::create([
                            'barang_id' => $record->barang_id,
                            'kode_barang' => $record->barang->kode_barang,
                            'kategori_id' => $record->barang->kategori_id,
                            'jumlah' => $record->jumlah_barang, // ini yang penting
                        ]);

                        // Buat transaksi keluar
                        TransaksiBarang::create([
                            'barang_id' => $record->barang_id,
                            'jenis_transaksi' => 'keluar',
                            'jumlah_barang' => $record->jumlah_barang,
                            'tanggal' => now(),
                            'status' => 'Disetujui',
                            'user_id' => $record->user_id,
                            'detail_barang_id' => $detail->id,
                        ]);

                        $record->barang->decrement('stok', $record->jumlah_barang);
                    }),

                Actions\Action::make('tolak')
                    ->label('Tolak')
                    ->color('danger')
                    ->icon('heroicon-m-x-mark')
                    ->visible(fn ($record) => $record->status === 'Menunggu')
                    ->requiresConfirmation()
                    ->action(fn (PengajuanBarang $record) => $record->update(['status' => 'Ditolak'])),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengajuanBarangs::route('/'),
        ];
    }
}
