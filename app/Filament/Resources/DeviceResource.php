<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeviceResource\Pages;
use App\Models\User;
use App\Models\Device;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DeviceResource extends Resource
{
    protected static ?string $model = Device::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Manajemen Device';
    protected static ?string $label = 'Device';
    protected static ?int $navigationSort = 1;

    // Hapus form jika tidak ingin menambah device dari sini
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Pemilik Device')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('nama')
                    ->label('Nama Device')
                    ->required(),

                Forms\Components\TextInput::make('spesifikasi')
                    ->label('Tipe Device')
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_serah_terima')
                    ->label('Tanggal Serah Terima')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif',
                        'tidak_aktif' => 'Tidak Aktif',
                        'perlu_perawatan' => 'Perlu Perawatan',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                // Ambil data unik berdasarkan user
                User::query()->withCount('devices')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pemilik')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('devices_count')
                    ->label('Jumlah Device')
                    ->sortable(),

                
            ])
            ->actions([
                Tables\Actions\Action::make('rincian')
                    ->label('Rincian Device')
                    ->icon('heroicon-o-computer-desktop')
                    ->url(fn ($record) => route('filament.admin.resources.devices.rincian-device', ['record' => $record->id])),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDevices::route('/'),
            'create' => Pages\CreateDevice::route('/create'),
            'edit' => Pages\EditDevice::route('/{record}/edit'),
            'rincian-device' => Pages\RincianDevice::route('/{record}/rincian-device'),
        ];
    }
}
