<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BidangMinatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bidang_minat')->insert([
            [
                'kode_bidang_minat' => 'BM001',
                'nama_bidang_minat' => 'Data Science',
            ],
            [
                'kode_bidang_minat' => 'BM002',
                'nama_bidang_minat' => 'Pengembangan Web',
            ],
            [
                'kode_bidang_minat' => 'BM003',
                'nama_bidang_minat' => 'Keamanan Siber',
            ],
            [
                'kode_bidang_minat' => 'BM004',
                'nama_bidang_minat' => 'Jaringan Komputer',
            ],
            [
                'kode_bidang_minat' => 'BM005',
                'nama_bidang_minat' => 'Kecerdasan Buatan',
            ],
        ]);
    }
}