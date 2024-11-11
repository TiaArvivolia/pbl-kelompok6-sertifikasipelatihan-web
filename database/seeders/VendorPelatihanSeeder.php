<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorPelatihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vendor_pelatihan')->insert([
            [
                'nama' => 'Pelatihan Indonesia',
                'kota' => 'Jakarta',
                'alamat' => 'Jl. Merdeka No. 10, Jakarta',
                'no_telepon' => '021-12345678',
                'website' => 'www.pelatihanindonesia.com',
            ],
            [
                'nama' => 'Global Training Center',
                'kota' => 'Bandung',
                'alamat' => 'Jl. Asia Afrika No. 22, Bandung',
                'no_telepon' => '022-98765432',
                'website' => 'www.globaltraining.com',
            ],
            [
                'nama' => 'Eduka Training',
                'kota' => 'Surabaya',
                'alamat' => 'Jl. Sudirman No. 30, Surabaya',
                'no_telepon' => '031-45678901',
                'website' => 'www.edukatraining.co.id',
            ],
        ]);
    }
}