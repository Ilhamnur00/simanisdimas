<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Device\DeviceController;
use App\Http\Controllers\Device\MaintenanceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Device & Maintenance
    Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
    Route::get('/devices/{device}', [DeviceController::class, 'show'])->name('devices.show');

    // Laporan perawatan
    Route::post('/device/{id}/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');

    // Riwayat perawatan
    Route::get('/device/{id}/riwayat', [DeviceController::class, 'riwayat'])->name('device.riwayat');
});

require __DIR__.'/auth.php';
