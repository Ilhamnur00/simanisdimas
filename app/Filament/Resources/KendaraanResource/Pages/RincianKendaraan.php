<?php

namespace App\Filament\Resources\KendaraanResource\Pages;

use App\Models\User;
use App\Models\Kendaraan;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class RincianKendaraan extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = \App\Filament\Resources\KendaraanResource::class;

    protected static string $view = 'custom.rincian-kendaraan';

    public $record;
    public User $user;

    public function mount($record): void
    {
        $this->record = $record;
        $this->user = User::findOrFail($record);
    }

    protected function getTableQuery()
    {
        return Kendaraan::query()->where('user_id', $this->user->id);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('nama')
                ->label('Nama Kendaraan'),

            Tables\Columns\TextColumn::make('kategori')
                ->label('Kategori'),

            Tables\Columns\TextColumn::make('spesifikasi')
                ->label('Spesifikasi'),

            Tables\Columns\TextColumn::make('tanggal_serah_terima')
                ->label('Tanggal Serah Terima')
                ->date('d-m-Y'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\EditAction::make()
                ->label('Edit')
                ->url(fn ($record) => \App\Filament\Resources\KendaraanResource::getUrl('edit', ['record' => $record]))
                ->icon('heroicon-o-pencil'),

            Tables\Actions\DeleteAction::make()
                ->label('Hapus')
                ->icon('heroicon-o-trash'),
        ];
    }

    public function getTitle(): string
    {
        return 'Kendaraan milik: ' . $this->user->name;
    }
}
