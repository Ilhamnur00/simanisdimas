<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Device\DeviceController;
use App\Http\Controllers\Device\MaintenanceController;
use App\Http\Controllers\Barang\BarangController;

// Redirect root URL ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// ========================
// 🔒 RUTE PROTEKSI (Login Diperlukan)
// ========================
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard utama
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // ======================== PROFIL PENGGUNA ========================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ======================== DEVICE ========================
    Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
    Route::get('/devices/{device}', [DeviceController::class, 'show'])->name('devices.show');

    // Laporan perawatan perangkat
    Route::post('/device/{id}/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');

    // Riwayat perawatan perangkat
    Route::get('/device/{id}/riwayat', [DeviceController::class, 'riwayat'])->name('device.riwayat');

    // ======================== BARANG ========================
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index'); // lihat stok
    Route::get('/barang/request', [BarangController::class, 'createRequest'])->name('barang.request'); // form permintaan
    Route::post('/barang/request', [BarangController::class, 'storeRequest'])->name('barang.request.store'); // simpan permintaan
    Route::get('/barang/history', [BarangController::class, 'history'])->name('barang.history'); // riwayat permintaan
});

// Rute auth (login, register, password reset, dll)
require __DIR__.'/auth.php';
