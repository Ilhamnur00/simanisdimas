<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Device\DeviceController;
use App\Http\Controllers\Device\MaintenanceController;
use App\Http\Controllers\Barang\BarangController;

// ========================
// 🔁 Redirect Root ke Login
// ========================
Route::get('/', function () {
    return redirect()->route('login');
});

// ========================
// 🔒 RUTE DENGAN PROTEKSI LOGIN & VERIFIKASI
// ========================
Route::middleware(['auth', 'verified'])->group(function () {

    // ======================== DASHBOARD ========================
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // ======================== PROFIL PENGGUNA ========================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ======================== DEVICE & MAINTENANCE ========================

    // Daftar semua device milik user
    Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');

    // Form laporan perawatan untuk device tertentu
    Route::get('/devices/{device}', [DeviceController::class, 'show'])->name('devices.show');

    // Simpan laporan perawatan (POST)
    Route::post('/device/{id}/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');

    // Riwayat perawatan untuk satu device
    Route::get('/device/{id}/riwayat', [DeviceController::class, 'riwayat'])->name('device.riwayat');

    // ✅ Riwayat semua perawatan dari semua device user (dengan dropdown filter)
    Route::get('/maintenance/riwayat', [MaintenanceController::class, 'riwayatAll'])->name('maintenance.riwayat.all');

    Route::get('/device/{deviceId}/riwayat', [MaintenanceController::class, 'riwayat'])->name('device.riwayat');
    Route::get('/riwayat', [MaintenanceController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat', [MaintenanceController::class, 'riwayatAll'])->name('device.riwayatAll');
    Route::get('/device/riwayat/all', [\App\Http\Controllers\Device\MaintenanceController::class, 'riwayatAll'])->name('device.riwayatAll');

    // ======================== BARANG ========================
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index'); // stok barang
    Route::get('/barang/request', [BarangController::class, 'createRequest'])->name('barang.request'); // form permintaan
    Route::post('/barang/request', [BarangController::class, 'storeRequest'])->name('barang.request.store'); // simpan permintaan
    Route::get('/barang/history', [BarangController::class, 'history'])->name('barang.history'); // riwayat permintaan
});

// ======================== RUTE AUTENTIKASI (LOGIN, REGISTER, DLL) ========================
require __DIR__.'/auth.php';
