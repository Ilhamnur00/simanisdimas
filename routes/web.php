<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Device\DeviceController;
use App\Http\Controllers\Device\MaintenanceController;
use App\Http\Controllers\Barang\BarangController;
use App\Http\Controllers\Kendaraan\KendaraanController;
use App\Http\Controllers\Kendaraan\PajakKendaraanController;

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
    Route::get('/', [BarangController::class, 'index'])->name('barang.index');

    // Form transaksi keluar (pengganti request)
    Route::get('/keluar', [BarangController::class, 'createTransaksiKeluar'])->name('barang.keluar');
    Route::post('/keluar', [BarangController::class, 'storeTransaksiKeluar'])->name('barang.keluar.store');

    // History transaksi keluar oleh user
    Route::get('/history', [BarangController::class, 'history'])->name('barang.history');

    // ======================== KENDARAAN ========================
    Route::get('/kendaraan', [KendaraanController::class, 'index'])->name('kendaraan.index');         // Tampilkan daftar kendaraan
    Route::post('/kendaraan', [KendaraanController::class, 'store'])->name('kendaraan.store');         // Simpan data baru
    Route::put('/kendaraan/{id}', [KendaraanController::class, 'update'])->name('kendaraan.update');   // Update data
    Route::delete('/kendaraan/{id}', [KendaraanController::class, 'destroy'])->name('kendaraan.destroy'); // Hapus data

    Route::get('/kendaraan', [PajakKendaraanController::class, 'index'])->name('transaksi.kendaraan');            // Tampilkan halaman transaksi kendaraan
    Route::post('/kendaraan', [PajakKendaraanController::class, 'store'])->name('transaksi.kendaraan.store');     // Simpan transaksi kendaraan
});

// Rute auth (login, register, password reset, dll)
require __DIR__.'/auth.php';