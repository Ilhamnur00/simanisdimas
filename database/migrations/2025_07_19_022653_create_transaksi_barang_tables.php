<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transaksi_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->nullable()->constrained('barang')->onDelete('cascade');
            $table->foreignId('detail_barang_id')->nullable()->constrained('detail_barang')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->enum('jenis_transaksi', ['masuk', 'keluar']);
            $table->date('tanggal');
            $table->unsignedInteger('jumlah_barang');
            $table->unsignedBigInteger('harga_satuan')->nullable(); // hanya jika transaksi masuk
            $table->unsignedBigInteger('total_harga')->nullable(); // hanya jika transaksi masuk
            $table->enum('status_asal', ['TKDN', 'PDN', 'IMPOR'])->nullable();
            $table->decimal('nilai_tkdn', 5, 2)->nullable();
            $table->enum('status', ['Disetujui', 'Ditolak', 'Menunggu'])->default('Menunggu');
            $table->timestamps();
        });

    }

    public function down(): void {
        Schema::dropIfExists('transaksi_barang');
    }
};
