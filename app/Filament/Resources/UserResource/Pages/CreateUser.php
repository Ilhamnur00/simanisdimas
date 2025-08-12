<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Throwable;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        try {
            return parent::handleRecordCreation($data);
        } catch (Throwable $e) {
            Notification::make()
                ->title('Gagal Menyimpan Data')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();

            throw $e; // tetap lempar error biar Filament tahu proses gagal
        }
    }
}

