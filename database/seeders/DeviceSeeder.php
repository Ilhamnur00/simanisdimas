<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DeviceSeeder extends Seeder
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

        // Isi data dummy device (semua kategori Laptop)
        $devices = [
            [
                'nama' => 'Laptop Dell Latitude 5420',
                'kategori' => 'Laptop',
                'spesifikasi' => 'Intel i5 Gen 11, 16GB RAM, 512GB SSD, Windows 11 Pro',
                'tanggal_serah_terima' => '2023-05-10',
            ],
            [
                'nama' => 'Laptop HP EliteBook 840 G8',
                'kategori' => 'Laptop',
                'spesifikasi' => 'Intel i7 Gen 11, 16GB RAM, 1TB SSD, Windows 11 Pro',
                'tanggal_serah_terima' => '2023-08-15',
            ],
            [
                'nama' => 'Laptop Lenovo ThinkPad X1 Carbon Gen 9',
                'kategori' => 'Laptop',
                'spesifikasi' => 'Intel i7 Gen 11, 16GB RAM, 512GB SSD, Windows 11 Pro',
                'tanggal_serah_terima' => '2024-01-20',
            ],
        ];


        foreach ($devices as $data) {
            Device::create(array_merge($data, [
                'user_id' => $user->id,
            ]));
        }

        $this->command->info('Seeder Device berhasil dijalankan.');
    }
}
