<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_kategori' => 'Elektronik', 'kode_kategori' => 'ELK'],
            ['nama_kategori' => 'Meubelair', 'kode_kategori' => 'MBL'],
            ['nama_kategori' => 'Alat Tulis Kantor', 'kode_kategori' => 'ATK'],
            ['nama_kategori' => 'Kendaraan', 'kode_kategori' => 'KND'],
            ['nama_kategori' => 'Jaringan', 'kode_kategori' => 'JRG'],
        ];

        foreach ($data as $item) {
            Kategori::firstOrCreate(['kode_kategori' => $item['kode_kategori']], $item);
        }
    }
}
