<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->decimal('harga_satuan', 14, 2)->nullable()->after('stok');
            $table->decimal('total_harga', 16, 2)->nullable()->after('harga_satuan');
            $table->enum('status_asal', ['TKDN', 'PDN', 'IMPOR'])->after('total_harga');
            $table->decimal('nilai_tkdn', 5, 2)->nullable()->after('status_asal');
        });
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn(['harga_satuan', 'total_harga', 'status_asal', 'nilai_tkdn']);
        });
    }
};
