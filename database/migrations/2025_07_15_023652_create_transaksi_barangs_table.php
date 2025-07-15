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
        Schema::create('transaksi_barang', function (Blueprint $table) {
            $table->id('no_transaksi');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');
            $table->enum('jenis_transaksi', ['masuk', 'keluar']);
            $table->integer('jumlah_barang');
            $table->decimal('harga_satuan', 12, 2)->nullable();
            $table->decimal('total_harga', 14, 2)->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->date('tanggal');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
