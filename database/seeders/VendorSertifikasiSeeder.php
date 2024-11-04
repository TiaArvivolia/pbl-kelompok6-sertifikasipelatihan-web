<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorSertifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vendor_sertifikasi')->insert([
            [
                'nama' => 'Sertifikasi Nasional',
                'alamat' => 'Jl. Diponegoro No. 15, Jakarta',
                'kota' => 'Jakarta',
                'no_telepon' => '021-87654321',
                'website' => 'www.sertifikasinasional.com',
            ],
            [
                'nama' => 'Certify International',
                'alamat' => 'Jl. Braga No. 5, Bandung',
                'kota' => 'Bandung',
                'no_telepon' => '022-12345678',
                'website' => 'www.certifyintl.com',
            ],
            [
                'nama' => 'Kompeten Sertifikasi',
                'alamat' => 'Jl. Pemuda No. 50, Yogyakarta',
                'kota' => 'Yogyakarta',
                'no_telepon' => '0274-7890123',
                'website' => 'www.kompetensertifikasi.com',
            ],
        ]);
    }
}