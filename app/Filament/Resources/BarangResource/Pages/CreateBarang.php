<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use App\Filament\Resources\TransaksiBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Models\Barang;

class CreateBarang extends CreateRecord
{
    protected static string $resource = BarangResource::class;

    private ?int $redirectBarangId = null; // Simpan ID barang untuk redirect

    protected function getCreateFormActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $normalizedName = strtolower(trim($data['nama_barang']));

        // Cek apakah barang sudah ada
        $existingBarang = Barang::whereRaw('LOWER(nama_barang) = ?', [$normalizedName])->first();

        if ($existingBarang) {
            Notification::make()
                ->title('Barang Sudah Ada')
                ->body('Barang "' . $existingBarang->nama_barang . '" sudah ada. Mengarahkan ke transaksi barang...')
                ->warning()
                ->send();

            $this->redirectBarangId = $existingBarang->id;

            // Return barang yang sudah ada supaya sesuai tipe Model
            return $existingBarang;
        }

        // Kalau belum ada, buat barang baru
        $barangBaru = static::getModel()::create($data);
        $this->redirectBarangId = $barangBaru->id;

        return $barangBaru;
    }

    protected function getRedirectUrl(): string
    {
        // Setelah barang dibuat atau ditemukan, arahkan ke create transaksi barang
        return TransaksiBarangResource::getUrl('create', [
            'barang_id' => $this->redirectBarangId
        ]);
    }
}
