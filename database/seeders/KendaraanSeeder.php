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
            // Jika tidak ada user, seeder batal dijalankan
            $this->command->warn('Tidak ada user ditemukan. Seeder Device tidak dijalankan.');
            return;
        }

        // Isi data dummy kendaraan (semua kategori)
        $kendaraans = [
            [
                'nama' => 'Motor Mio',
                'kategori' => 'Motor',
                'spesifikasi' => '-',
                'tanggal_serah_terima' => '2024-09-10',
            ],
            [
                'nama' => 'Mobil Avanza',
                'kategori' => 'Mobil',
                'spesifikasi' => '-',
                'tanggal_serah_terima' => '2025-07-15',
            ],
            [
                'nama' => 'Motor Bebek',
                'kategori' => 'Motor',
                'spesifikasi' => 'I-',
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
