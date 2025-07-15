<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode_kategori' => 'ATK', 'nama_kategori' => 'Alat Tulis Kantor'],
            ['kode_kategori' => 'ELK', 'nama_kategori' => 'Alat Listrik'],
            ['kode_kategori' => 'KRT', 'nama_kategori' => 'Kertas'],
            ['kode_kategori' => 'CLN', 'nama_kategori' => 'Cairan Pembersih'],
            ['kode_kategori' => 'BHK', 'nama_kategori' => 'Bahan Komputer'],
            ['kode_kategori' => 'PRC', 'nama_kategori' => 'Percetakan'],
        ];

        foreach ($data as $kategori) {
            Kategori::create($kategori);
        }
    }
}
