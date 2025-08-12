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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;

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
                ->readOnly()
                ->disabledOn('edit'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_barang')
                    ->searchable(),

                TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->searchable()
                    ->url(fn (Barang $record) => static::getUrl('edit', ['record' => $record])),

                TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori'),

                TextColumn::make('detail_barang_sum_jumlah')
                    ->label('Stok Tersedia')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ?? 0),
            ])
            ->filters([
                Filter::make('stok_habis')
                    ->label('Stok Habis')
                    ->query(fn (Builder $query) =>
                        $query->having('detail_barang_sum_jumlah', '=', 0)
                    ),

                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Rincian')
                    ->icon('heroicon-s-document-magnifying-glass')
                    ->url(fn (Barang $record) => static::getUrl('rincian', ['record' => $record])),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(), // Soft delete
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        // Query utama barang + stok sum
        return parent::getEloquentQuery()
            ->withSum('detailBarang', 'jumlah');
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
