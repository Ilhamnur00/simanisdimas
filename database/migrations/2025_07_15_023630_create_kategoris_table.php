<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kategori', 3)->unique(); // contoh: ATK, ELK
            $table->string('nama_kategori', 100);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('kategori');
    }
};
