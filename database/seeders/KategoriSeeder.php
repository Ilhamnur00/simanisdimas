<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            ['kode_kategori' => 'ADP', 'nama_kategori' => 'Alat Dapur'],
            ['kode_kategori' => 'ATK', 'nama_kategori' => 'Alat Tulis Kantor'],
            ['kode_kategori' => 'BBK', 'nama_kategori' => 'Bahan Bakar'],
            ['kode_kategori' => 'DEV', 'nama_kategori' => 'DEVICE'],
            ['kode_kategori' => 'ELK', 'nama_kategori' => 'Elektronik'],
            ['kode_kategori' => 'KBS', 'nama_kategori' => 'Kebersihan'],
            ['kode_kategori' => 'LNN', 'nama_kategori' => 'Lainnya'],
            ['kode_kategori' => 'MEB', 'nama_kategori' => 'Mebel'],
            ['kode_kategori' => 'MNM', 'nama_kategori' => 'Minuman'],
        ];

        DB::table('kategori')->insert($kategori);
    }
}
