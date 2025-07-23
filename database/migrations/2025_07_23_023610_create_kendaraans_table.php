<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * Membuat tabel 'kendaraans' untuk menyimpan data master dan transaksi kendaraan dinas.
     */
    public function up(): void
    {
        Schema::create('kendaraans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke users
        $table->string('nama');
        $table->string('kategori')->nullable();
        $table->text('spesifikasi')->nullable();
        $table->date('tanggal_serah_terima')->nullable(); // Tambahan
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};
