<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal'); // Tanggal perawatan
            $table->string('kategori_perawatan'); // Contoh: Ganti Part, Cleaning, Upgrade, dll
            $table->text('deskripsi'); // Penjelasan tambahan perawatan
            $table->string('status')->default('Selesai'); // Status pengerjaan: Selesai / Pending / Dalam Proses
            $table->string('bukti')->nullable(); // Path file bukti, bisa PDF, JPG, PNG
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
