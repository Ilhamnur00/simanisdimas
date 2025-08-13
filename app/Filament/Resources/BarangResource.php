<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Models\Barang;
use App\Models\Kategori;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{TextInput, Select};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Validation\Rule;
use Closure;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;
    protected static ?string $navigationGroup = 'Manajemen Barang';
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Barang';
    protected static ?string $pluralModelLabel = 'Daftar Barang';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('kategori_id')
                ->label('Kategori')
                ->relationship('kategori', 'nama_kategori')
                ->required()
                ->reactive()
                ->disabledOn('edit'),

            TextInput::make('nama_barang')
                ->label('Nama Barang')
                ->required()
                ->rules([
                    function (string $attribute, $value, Closure $fail) {
                        $exists = Barang::whereRaw('LOWER(nama_barang) = ?', [strtolower($value)])
                            ->when(request()->route('record'), fn($q) =>
                                $q->where('id', '!=', request()->route('record'))
                            )
                            ->exists();

                        if ($exists) {
                            $fail('Nama barang sudah ada, silakan gunakan nama lain.');
                        }
                    }
                ])
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $kategori = Kategori::find($get('kategori_id'));
                    if ($kategori) {
                        $prefix = $kategori->kode_kategori;
                        $count = Barang::where('kategori_id', $kategori->id)->count() + 1;
                        $kodeBarang = $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
                        $set('kode_barang', $kodeBarang);
                    }
                }),

            TextInput::make('kode_barang')
                ->label('Kode Barang')
                ->required()
                ->rules([
                    function (string $attribute, $value, Closure $fail) {
                        $exists = Barang::whereRaw('LOWER(kode_barang) = ?', [strtolower($value)])
                            ->when(request()->route('record'), fn($q) =>
                                $q->where('id', '!=', request()->route('record'))
                            )
                            ->exists();

                        if ($exists) {
                            $fail('Kode barang sudah ada, silakan gunakan kode lain.');
                        }
                    }
                ])
                ->readOnly()
                ->disabledOn('edit'),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_barang')->label('Kode')->searchable(),
                TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->searchable()
                    ->url(fn (Barang $record) => static::getUrl('edit', ['record' => $record])),
                TextColumn::make('kategori.nama_kategori')->label('Kategori'),
                TextColumn::make('stok')
                    ->label('Stok Tersedia'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('stok_aktif')
                    ->label('Stok Aktif')
                    ->button()
                    ->action(fn ($livewire) => $livewire->redirect(static::getUrl('index', ['status' => 'aktif']))),
                Tables\Actions\Action::make('stok_habis')
                    ->label('Stok Habis')
                    ->color('danger')
                    ->button()
                    ->action(fn ($livewire) => $livewire->redirect(static::getUrl('index', ['status' => 'habis']))),
            ])
            ->actions([
                
                Tables\Actions\Action::make('Lihat Detail')
                    ->icon('heroicon-s-document-magnifying-glass')
                    ->label('Rincian')
                    ->url(fn (Barang $record) => static::getUrl('rincian', ['record' => $record])),

                DeleteAction::make()
                    ->label('Hapus')
                    ->action(function (Barang $record) {
                        $hasRelasi = $record->detailBarang()->exists() || $record->transaksiBarang()->exists();
                        if ($hasRelasi) {
                            $record->delete(); // soft delete
                        } else {
                            $record->forceDelete(); // hapus permanen
                        }
                    })
                    ->visible(fn (Barang $record) => $record->stok === 0),
            ])
            ->modifyQueryUsing(function ($query) {
                $status = request()->query('status', 'aktif');
                if ($status === 'habis') {
                    $query->whereDoesntHave('detailBarang')
                          ->orWhereHas('detailBarang', fn ($q) => $q->selectRaw('SUM(jumlah) as total')->havingRaw('total = 0'));
                } else {
                    $query->whereHas('detailBarang', fn ($q) => $q->where('jumlah', '>', 0));
                }
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
            'rincian' => Pages\RincianBarang::route('/{record}'),
        ];
    }
}
