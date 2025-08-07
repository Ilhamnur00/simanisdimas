<?php

namespace App\Filament\Pages;

use App\Models\LaporanPerawatan;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class RiwayatPerawatanKendaraan extends Page implements HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationLabel = 'Riwayat Perawatan';
    protected static ?string $navigationGroup = 'Manajemen Kendaraan';
    protected static string $view = 'filament.pages.riwayat-perawatan-kendaraan';
    protected static ?int $navigationSort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(LaporanPerawatan::query()->with(['kendaraan.user']))
            ->defaultSort('tanggal', 'desc')
            ->columns([
                TextColumn::make('kendaraan.user.name')
                    ->label('Nama User')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kendaraan.nama')
                    ->label('Nama Kendaraan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kategori_perawatan')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(40)
                    ->wrap(),

                TextColumn::make('bukti')
                    ->label('Bukti')
                    ->formatStateUsing(function ($state) {
                        if (!$state) {
                            return '<span class="text-blue-700 italic">-</span>';
                        }

                        $url = asset('storage/' . $state);

                        return '<a href="' . $url . '" target="_blank" class="text-blue-600 hover:underline font-semibold">Lihat</a>';
                    })
                    ->html(),

                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date()
                    ->sortable()
                    ->date(format: 'd-m-Y'),
            ]);
    }
}
