<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('jenis_pengguna')->insert([
            [
                'kode_jenis_pengguna' => 'ADM',
                'nama_jenis_pengguna' => 'Administrator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_jenis_pengguna' => 'DSN',
                'nama_jenis_pengguna' => 'Dosen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_jenis_pengguna' => 'TNDK',
                'nama_jenis_pengguna' => 'Tendik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_jenis_pengguna' => 'PMPN',
                'nama_jenis_pengguna' => 'Pimpinan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}