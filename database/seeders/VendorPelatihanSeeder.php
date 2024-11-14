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
    public function run()
    {
        DB::table('vendor_pelatihan')->insert([
            [
                'nama' => 'Inixindo Surabaya',
                'kota' => 'Surabaya',
                'alamat' => 'Jl. Raya Inixindo No. 10 Surabaya',
                'no_telepon' => '0311234567',
                'website' => 'https://inixindo-surabaya.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Inixindo Jogja',
                'kota' => 'Yogyakarta',
                'alamat' => 'Jl. Malioboro No. 20 Yogyakarta',
                'no_telepon' => '0274123456',
                'website' => 'https://inixindo-jogja.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Brainmatix',
                'kota' => null, // No city specified
                'alamat' => 'Jl. Citra Raya No. 30 Jakarta',
                'no_telepon' => '0219876543',
                'website' => 'https://brainmatix.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Binus Jakarta',
                'kota' => 'Jakarta',
                'alamat' => 'Jl. KH. Wahid Hasyim No. 12 Jakarta',
                'no_telepon' => '0219988776',
                'website' => 'https://binusjakarta.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
