<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class KendaraanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $kategoriList = ['Mobil', 'Motor', 'Truk'];
        $data = [];

        for ($i = 1; $i <= 20; $i++) {
            $userId = ($i % 5) + 1;
            $kategori = $faker->randomElement($kategoriList);

            $noPolisi = strtoupper($faker->randomLetter) . " " .
                        $faker->numberBetween(1000, 9999) . " " .
                        strtoupper($faker->lexify('???'));

            $data[] = [
                'user_id' => $userId,
                'nama' => $kategori . ' ' . $faker->company,
                'no_polisi' => $noPolisi,
                'kategori' => $kategori,
                'spesifikasi' => $faker->sentence(8),
                'tanggal_serah_terima' => $faker->dateTimeBetween('-5 years', '-1 years')->format('Y-m-d'),
                'tanggal_pajak' => $faker->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
                'reminder_h7_sent' => false,
                'reminder_h0_sent' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('kendaraans')->insert($data);
    }
}