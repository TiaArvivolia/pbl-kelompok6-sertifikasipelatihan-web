<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPelatihanSeeder extends Seeder
{
    public function run()
    {
        DB::table('jenis_pelatihan')->insert([
            [
                'nama_jenis_pelatihan' => 'Data Science',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis_pelatihan' => 'Jaringan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis_pelatihan' => 'Game Development',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}