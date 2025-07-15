<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\User;
use Carbon\Carbon;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada user dulu
        $user = User::first() ?? User::factory()->create([
            'name' => 'Default User',
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        Device::create([
            'user_id' => $user->id,
            'nama' => 'Laptop ASUS ROG',
            'spesifikasi' => 'Intel i7, 16GB RAM, 512GB SSD',
            'tanggal_serah_terima' => Carbon::now()->subDays(10),
        ]);

        Device::create([
            'user_id' => $user->id,
            'nama' => 'MacBook Pro M1',
            'spesifikasi' => 'Apple M1, 8GB RAM, 256GB SSD',
            'tanggal_serah_terima' => Carbon::now()->subDays(30),
        ]);

        Device::create([
            'user_id' => $user->id,
            'nama' => 'Lenovo ThinkPad',
            'spesifikasi' => 'Intel i5, 8GB RAM, 1TB HDD',
            'tanggal_serah_terima' => Carbon::now()->subDays(60),
        ]);
    }
}
