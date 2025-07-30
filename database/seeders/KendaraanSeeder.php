<?php

namespace Database\Seeders;

use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KendaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user pertama (pastikan minimal 1 user ada di DB)
        $user = User::first();

        if (!$user) {
            $this->command->warn('Tidak ada user ditemukan. Seeder Kendaraan tidak dijalankan.');
            return;
        }

        // Isi data dummy kendaraan (lengkap dengan no_polisi)
        $kendaraans = [
            [
                'nama' => 'Motor Mio',
                'no_polisi' => 'B1234MI',
                'kategori' => 'Motor',
                'spesifikasi' => '-',
                'tanggal_serah_terima' => '2024-09-10',
            ],
            [
                'nama' => 'Mobil Avanza',
                'no_polisi' => 'D5678AV',
                'kategori' => 'Mobil',
                'spesifikasi' => 'Manual',
                'tanggal_serah_terima' => '2025-07-15',
            ],
            [
                'nama' => 'Motor Bebek',
                'no_polisi' => 'F9012BK',
                'kategori' => 'Motor',
                'spesifikasi' => 'Injeksi',
                'tanggal_serah_terima' => '2025-03-20',
            ],
        ];

        foreach ($kendaraans as $data) {
            Kendaraan::create(array_merge($data, [
                'user_id' => $user->id,
            ]));
        }

        $this->command->info('Seeder Kendaraan berhasil dijalankan.');
    }
}
