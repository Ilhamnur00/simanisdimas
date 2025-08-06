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
        Schema::create('pajak_kendaraan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kendaraan_id');
            $table->string('jenis_pajak');
            $table->date('tanggal');
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            // Foreign key ke tabel kendaraan
            $table->foreign('kendaraan_id')->references('id')->on('kendaraans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pajak_kendaraan');
    }
};
