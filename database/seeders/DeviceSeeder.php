<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;
use Carbon\Carbon;

class DeviceSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        $devices = [
            // User 1
            [
                'user_id' => 1,
                'nama' => 'Laptop Dell Latitude 5420',
                'kategori' => 'Laptop',
                'spesifikasi' => 'Intel Core i5-1135G7, 16GB RAM, 512GB SSD, Windows 11 Pro',
                'tanggal_serah_terima' => '2024-01-15',
            ],
            [
                'user_id' => 1,
                'nama' => 'Printer Canon LBP6030',
                'kategori' => 'Printer',
                'spesifikasi' => 'Laser Monochrome, USB Connection',
                'tanggal_serah_terima' => '2024-02-10',
            ],

            // User 2
            [
                'user_id' => 2,
                'nama' => 'PC Rakitan Office',
                'kategori' => 'PC',
                'spesifikasi' => 'Intel Core i3-10100, 8GB RAM, 256GB SSD, Windows 10 Pro',
                'tanggal_serah_terima' => '2024-03-05',
            ],
            [
                'user_id' => 2,
                'nama' => 'Monitor LG 24MK600',
                'kategori' => 'Monitor',
                'spesifikasi' => '24 inch IPS, Full HD 1080p',
                'tanggal_serah_terima' => '2024-03-06',
            ],

            // User 3
            [
                'user_id' => 3,
                'nama' => 'Laptop HP ProBook 440 G8',
                'kategori' => 'Laptop',
                'spesifikasi' => 'Intel Core i7-1165G7, 16GB RAM, 1TB SSD, Windows 11 Pro',
                'tanggal_serah_terima' => '2024-04-01',
            ],
            [
                'user_id' => 3,
                'nama' => 'Scanner Epson V39',
                'kategori' => 'Scanner',
                'spesifikasi' => 'Flatbed, 4800 dpi, USB Power',
                'tanggal_serah_terima' => '2024-04-03',
            ],

            // User 4
            [
                'user_id' => 4,
                'nama' => 'Tablet Samsung Galaxy Tab S6 Lite',
                'kategori' => 'Tablet',
                'spesifikasi' => '10.4 inch, 4GB RAM, 64GB Storage, Android 13',
                'tanggal_serah_terima' => '2024-05-12',
            ],
            [
                'user_id' => 4,
                'nama' => 'Headset Logitech H390',
                'kategori' => 'Headset',
                'spesifikasi' => 'USB Connection, Noise Cancelling Microphone',
                'tanggal_serah_terima' => '2024-05-14',
            ],

            // User 5
            [
                'user_id' => 5,
                'nama' => 'Laptop Lenovo ThinkPad E14 Gen 4',
                'kategori' => 'Laptop',
                'spesifikasi' => 'Intel Core i5-1240P, 16GB RAM, 512GB SSD, Windows 11 Pro',
                'tanggal_serah_terima' => '2024-06-20',
            ],
            [
                'user_id' => 5,
                'nama' => 'Printer Epson L3150',
                'kategori' => 'Printer',
                'spesifikasi' => 'Ink Tank, Print/Scan/Copy, WiFi',
                'tanggal_serah_terima' => '2024-06-21',
            ],
        ];

        foreach ($devices as $device) {
            Device::create($device);
        }
    }
}
