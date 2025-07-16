<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaksi_barang', function (Blueprint $table) {
            $table->enum('status_asal', ['TKDN', 'PDN', 'IMPOR'])->after('status')->nullable();
            $table->decimal('nilai_tkdn', 5, 2)->after('status_asal')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_barang', function (Blueprint $table) {
            $table->dropColumn(['status_asal', 'nilai_tkdn']);
        });
    }
};
