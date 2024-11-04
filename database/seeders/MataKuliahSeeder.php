<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mata_kuliah')->insert([
            [
                'kode_mk' => 'MK001',
                'nama_mk' => 'Statistika Dasar',
            ],
            [
                'kode_mk' => 'MK002',
                'nama_mk' => 'Machine Learning',
            ],
            [
                'kode_mk' => 'MK003',
                'nama_mk' => 'Pemrograman Web',
            ],
            [
                'kode_mk' => 'MK004',
                'nama_mk' => 'Keamanan Jaringan',
            ],
            [
                'kode_mk' => 'MK005',
                'nama_mk' => 'Pengantar Kecerdasan Buatan',
            ],
        ]);
    }
}