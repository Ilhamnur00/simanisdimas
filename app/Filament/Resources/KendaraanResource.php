<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KendaraanResource\Pages;
use App\Models\Kendaraan;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KendaraanResource extends Resource
{
    protected static ?string $model = Kendaraan::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Manajemen Kendaraan';
    protected static ?string $label = 'Kendaraan';
    protected static ?string $pluralLabel = 'Kendaraan';
    protected static ?int $navigationSort = 1;


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('user', function ($query) {
                $query->whereHas('kendaraans');
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Pemilik Kendaraan')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('nama')
                    ->label('Nama Kendaraan')
                    ->required(),

                Forms\Components\TextInput::make('no_polisi')
                    ->label('Nomor Polisi')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('Contoh: R1234AB')
                    ->maxLength(12),

                Forms\Components\Select::make('kategori')
                    ->label('Kategori')
                    ->options([
                        'Motor' => 'Motor',
                        'Mobil' => 'Mobil',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('spesifikasi')
                    ->label('Spesifikasi')
                    ->rows(4),

                Forms\Components\DatePicker::make('tanggal_serah_terima')
                    ->label('Tanggal Serah Terima')
                    ->default(now())
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_pajak')
                    ->label('Tanggal Pajak Tahunan')
                    ->required()
                    ->default(now()->addYear()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->withCount('kendaraans')
                    ->having('kendaraans_count', '>', 0)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pemilik')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('kendaraans_count')
                    ->label('Jumlah Kendaraan')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('rincian')
                    ->label('Rincian Kendaraan')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('filament.admin.resources.kendaraans.rincian-kendaraan', ['record' => $record->id])),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKendaraans::route('/'),
            'create' => Pages\CreateKendaraan::route('/create'),
            'edit' => Pages\EditKendaraan::route('/{record}/edit'),
            'rincian-kendaraan' => Pages\RincianKendaraan::route('/{record}/rincian-kendaraan'),
        ];
    }
}
