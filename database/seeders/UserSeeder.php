<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $super_admin = User::firstOrCreate([
            'email' => 'ilham.nurfajri121@gmail.com',
        ], [
            'name' => 'Ilham Nur Fajri',
            'nip' => '2211102295',
            'password' => bcrypt('password'),
        ]);
        $super_admin->assignRole('super_admin');

        $super_admin = User::firstOrCreate([
            'email' => 'hernandiradenbagus@gmail.com',
        ], [
            'name' => 'Raden Bagus Hernandi',
            'nip' => '2211102343',
            'password' => bcrypt('password'),
        ]);
        $super_admin->assignRole('super_admin');

        $super_admin = User::firstOrCreate([
            'email' => 'ainnunnisa123@gmail.com',
        ], [
            'name' => 'Ainnun Nisa Khutrunnada',
            'nip' => '2211102351',
            'password' => bcrypt('password'),
        ]);
        $super_admin->assignRole('super_admin');

        $super_admin = User::firstOrCreate([
            'email' => 'srpuspta@gmail.com',
        ], [
            'name' => 'Ike Puspita sari',
            'nip' => '2211102312',
            'password' => bcrypt('password'),
        ]);
        $super_admin->assignRole('super_admin');

        $admin = User::firstOrCreate([
            'email' => 'ilham@gmail.com',
        ], [
            'name' => 'ilham admin',
            'nip' => '12121212', // ← 
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        $user = User::firstOrCreate([
            'email' => '2211102343@ittelkom-pwt.ac.id',
        ], [
            'name' => 'raden user',
            'nip' => '1122334455667788',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('user');
    }
}
