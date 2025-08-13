<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kategori;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Alat Dapur' => [
                'Container Box Bahan Plastik',
                'Garpu makan',
                'Keset Kain Tebal Besar',
                'Mangkok sedang',
                'Panci alumunium diameter 45cm',
                'Sapu Cemara',
                'Sendok makan',
                'Tabung Gas 12kg',
                'Ember Plastik',
                'Gelas panjang kaca D : 8cm T : 13cm',
                'Gelas tangkai',
                'Kain pel',
                'Kran Wastafel 1/2”',
                'Lap kain kotak-kotak (merah&biru)',
            ],
            'Alat Tulis Kantor' => [
                'Materai',
                'Ballpoint : Faster',
                'Ballpoint : Pilot Balliner',
                'Ballpoint : Standard AE 7',
                'Binder Clip 107 warna set',
                'Binder Clip 200 24C',
                'Dus Arsip Lambang Daerah',
                'File Box Besar',
                'Gunting Besar',
                'Hechmachine : Besar (No. 24/6)',
                'Hechmachine : Kecil (No. 10)',
                'Hecht Neiches : Kecil (No. 10)',
                'Karet Penghapus standar',
                'Lakban Besar',
                'Lakban Kecil',
                'Lem Cair Ukuran Tanggung',
                'Ordner : Folio',
                'Paper Clip',
                'Penggaris Besi 60cm',
                'Penggaris Mica Ukuran 30cm',
                'Penghapus Cair/Correction Fluid (uk. besar)',
                'Pensil Hitam 2B merah/Biru',
                'Perforator Besar',
                'Perforator Kecil No. 40',
                'Pisau Cutter Besar (L500)',
                'Pisau Cutter Kecil (A300)',
                'Post-It/Penanda Kertas',
                'Snelhecter : Biasa Folio',
                'Snelhecter : Plastik',
                'Spidol Besar/White Board',
                'Spidol Permanent Black/Red/Blue',
                'Stabilo Besar (10 buah)',
                'Stempel Flash Stamp Otomatis',
                'Stempel Kayu',
                'Stopmap Biasa Folio',
                'Stopmap Plastik',
                'Tinta Stempel',
                'Amplop Coklat Besar',
                'Amplop kecil',
                'Amplop polos',
                'Kertas HVS 70gr Folio/F4',
                'Kertas HVS 70gr Folio/F4 warna',
                'Kertas HVS 80gr Folio/F4',
                'Kertas Kwarto/A4 70gr',
                'ID Card',
                'Cetak Cover Luar Lux',
                'Foto Copy : Folio',
                'Jilid Lakban tebal > 5cm',
                'Jilid Soft Cover tebal < 5cm',
                'Amplop Kop Dinas uk. Besar Kertas Cassing',
                'Amplop Kop Dinas uk. Kabinet Kertas Cassing',
                'Baliho',
                'Banner',
                'Belanja Cetak Buku Data Statistik Kabupaten Banyumas',
                'Kartu Kendali NCR (masuk/keluar)',
                'Papan Bahan Arcylic',
                'STPP dan Stopmap Diklat Kepemimpinan Tk. III',
                'Binder Clip 155 24C',
            ],
            'Bahan Bakar' => [
                'Pertamax',
                'Isi Tabung LPG 5kg',
            ],
            'DEVICE' => [
                'Flashdisk 64GB',
                'USB Hub 3.0',
                'Keyboard Standard',
                'Mouse Wirelees',
            ],
            'Elektronik' => [
                'Baterai AAA',
                'Baterai Besar',
                'Batu Baterai Kalkulator',
                'Kabel roll 25m',
                'Lampu Hemat Energi 14w',
                'Lampu Hemat Energi 20w',
                'Lampu Hemat Energi 30w',
                'Lampu Hemat Energi LED 19 w',
                'Saklar + stop kontak',
                'Stop kontak 6 lubang',
                'Access Point Indoor',
                'Keyboard Wireless',
                'LCD Proyektor',
                'Air Conditioner (AC) Kapasitas 1 PK',
                'LED TV 43”',
                'Laptop Processor Core i5',
                'Document Scanner',
                'Monitor LED 24”',
                'Laser Pointer',
                'Printer InkJet',
                'Stop Kontak',
            ],
            'Kebersihan' => [
                'Tisu Rol',
                'Cairan pembersih lantai',
                'Cairan Pemutih dan disinfektan',
                'Pembersih Keramik (Porstek)',
                'Sabun Cuci 450gram (isi 24); Deterjen Bubuk, Kemasan Sachet',
                'Sabun Cuci piring cair kemas',
                'Sabun Cuci tangan 250ml',
                'Semir Ban',
                'Cairan Pembersih kaca',
                'Kapur barus',
                'Pengharum Kamar mandi',
                'Pengharum mobil',
                'Pengharum ruangan elektronik',
                'Kanebo',
                'Tisu isi 250 sheets',
                'Cairan pembersih kamar mandi',
            ],
            'Lainnya' => [
                'Karangan Bunga',
            ],
            'Mebel' => [
                'Rak arsip',
                'Filing Cabinet Besi',
                'Papan Informasi',
                'Almari',
            ],
            'Minuman' => [
                'Air mineral botol kecil',
                'Air mineral gelas',
                'Air minum Isi Ulang',
                'Gula Pasir',
                'Kopi',
                'Teh',
            ],
        ];

        foreach ($data as $kategoriNama => $barangs) {
            $kategori = Kategori::where('nama_kategori', $kategoriNama)->first();
            if (!$kategori) continue;

            foreach ($barangs as $barang) {
                // Insert barang tanpa kode_barang
                $newBarang = Barang::create([
                    'kategori_id' => $kategori->id,
                    'nama_barang' => $barang,
                ]);

                // Update kode_barang setelah tahu ID
                $newBarang->kode_barang = $kategori->kode_kategori . str_pad($newBarang->id, 3, '0', STR_PAD_LEFT);
                $newBarang->save();
            }
        }
    }
}
