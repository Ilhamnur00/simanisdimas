<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Models\User;
use App\Models\Device;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class RincianDevice extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = \App\Filament\Resources\DeviceResource::class;

    protected static string $view = 'custom.rincian-device';

    public $record;
    public User $user;

    public function mount($record): void
    {
        $this->record = $record;
        $this->user = User::findOrFail($record);
    }

    protected function getTableQuery()
    {
        return Device::query()->where('user_id', $this->user->id);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('nama')->label('Nama Device'),
            Tables\Columns\TextColumn::make('spesifikasi')->label('Spesifikasi'),
            Tables\Columns\TextColumn::make('tanggal_serah_terima')->label('Tanggal Serah Terima')->date('d-m-Y'),
            Tables\Columns\TextColumn::make('status')->label('Status'),
            
        ];
    }

    public function getTitle(): string
    {
        return 'Device milik: ' . $this->user->name;
    }
}
