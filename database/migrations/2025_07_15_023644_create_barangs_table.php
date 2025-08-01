<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
            $table->string('nama_barang');
            $table->string('kode_barang')->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('barang');
    }
};
