<?php

namespace App\Filament\Resources\BarangResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Models\TransaksiBarang;
use App\Models\Barang;

class RiwayatTransaksiTable extends TableWidget
{
    protected static ?string $heading = 'Riwayat Transaksi Barang';
    protected static ?int $sort = 2;

    public ?Barang $record = null;
    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return TransaksiBarang::query()
            ->where('barang_id', $this->record?->id)
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('tanggal')
                ->label('Tanggal')
                ->date()
                ->sortable(),

            TextColumn::make('jenis_transaksi')
                ->label('Jenis')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'masuk' => 'success',
                    'keluar' => 'danger',
                    default => 'gray',
                }),

            TextColumn::make('jumlah_barang')
                ->label('Jumlah')
                ->sortable(),

            TextColumn::make('harga_satuan')
                ->label('Harga Satuan')
                ->money('IDR', true),

            TextColumn::make('total_harga')
                ->label('Total Harga')
                ->money('IDR', true),

            TextColumn::make('status_asal')
                ->label('Asal Pengadaan')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'TKDN' => 'success',
                    'PDN' => 'primary',
                    'IMPOR' => 'warning',
                    default => 'gray',
                }),

            TextColumn::make('nilai_tkdn')
                ->label('Nilai TKDN')
                ->suffix('%')
                ->visible(fn ($record) => $record?->status_asal === 'TKDN'),
        ];
    }
}
