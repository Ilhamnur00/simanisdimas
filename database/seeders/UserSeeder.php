<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        $super_admin = User::firstOrCreate([
            'email' => 'ainnunnisa123@gmail.com',
        ], [
            'name' => 'Admin Dinkominfo',
            'nip' => '2211102295', // ← tambahkan NIP valid
            'password' => bcrypt('password'),
        ]);
        $super_admin->assignRole('super_admin');

        $admin = User::firstOrCreate([
            'email' => 'ilham.nurfajri121@gmail.com',
        ], [
            'name' => 'Admin barang',
            'nip' => '1234567890', // ← 
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        $user = User::firstOrCreate([
            'email' => 'hernandiradenbagus@gmail.com',
        ], [
            'name' => 'User Umum',
            'nip' => '1122334455', // ← tambahkan NIP juga
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('super_admin');
    }
}
