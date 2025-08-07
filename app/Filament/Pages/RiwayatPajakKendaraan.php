<?php

namespace App\Filament\Pages;

use App\Models\LaporanPajak;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class RiwayatPajakKendaraan extends Page implements HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Riwayat Pajak';
    protected static ?string $navigationGroup = 'Manajemen Kendaraan';
    protected static string $view = 'filament.pages.riwayat-pajak-kendaraan';

    public function table(Table $table): Table
    {
        return $table
            ->query(LaporanPajak::query()->with(['kendaraan.user']))
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

                TextColumn::make('jenis_pajak')
                    ->label('Jenis Pajak')
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
                    ->sortable(),
            ]);
    }
}
