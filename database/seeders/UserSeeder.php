<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        $super_admin = User::firstOrCreate([
            'email' => 'admin@inventory.com',
        ], [
            'name' => 'Admin Dinkominfo',
            'nip' => '2211102295', // ← tambahkan NIP valid
            'password' => bcrypt('password'),
        ]);
        $super_admin->assignRole('super_admin');

        $admin = User::firstOrCreate([
            'email' => 'admin@inventory22.com',
        ], [
            'name' => 'Admin barang',
            'nip' => '1234567890', // ← tambahkan NIP valid
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        $user = User::firstOrCreate([
            'email' => 'user@inventory.com',
        ], [
            'name' => 'User Umum',
            'nip' => '0987654321', // ← tambahkan NIP juga
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('user');
    }
}
