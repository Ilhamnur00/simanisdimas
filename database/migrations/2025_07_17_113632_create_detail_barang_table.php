<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('detail_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');
            $table->enum('status_asal', ['TKDN', 'PDN', 'IMPOR'])->nullable();
            $table->decimal('nilai_tkdn', 5, 2)->nullable();
            $table->unsignedInteger('jumlah');
            $table->unsignedBigInteger('harga_satuan')->nullable();
            $table->unsignedBigInteger('total_harga')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('detail_barang');
    }
};
