<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Kategori;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_barang' => 'Laptop Lenovo Thinkpad', 'kode_kategori' => 'BHK', 'stok' => 10],
            ['nama_barang' => 'Printer Epson L3110', 'kode_kategori' => 'BHK', 'stok' => 5],
            ['nama_barang' => 'Pulpen Biru', 'kode_kategori' => 'ATK', 'stok' => 100],
            ['nama_barang' => 'Kertas A4 80gsm', 'kode_kategori' => 'KRT', 'stok' => 50],
            ['nama_barang' => 'Kursi Kantor', 'kode_kategori' => 'ATK', 'stok' => 8],
        ];

        foreach ($data as $item) {
            $kategori = Kategori::where('kode_kategori', $item['kode_kategori'])->first();

            if ($kategori) {
                $jumlahSebelumnya = Barang::where('kategori_id', $kategori->id)->count() + 1;
                $urutan = str_pad($jumlahSebelumnya, 3, '0', STR_PAD_LEFT);
                $kodeBarang = $kategori->kode_kategori . $urutan;

                Barang::create([
                    'kode_barang' => $kodeBarang,
                    'nama_barang' => $item['nama_barang'],
                    'kategori_id' => $kategori->id,
                    'stok' => $item['stok'],
                ]);
            }
        }
    }
}
