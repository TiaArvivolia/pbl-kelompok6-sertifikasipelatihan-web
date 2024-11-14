<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TendikSeeder extends Seeder
{
    public function run()
    {
        DB::table('tendik')->insert([
            [
                'id_pengguna' => 1, 
                'nama_lengkap' => 'Siti Nurhaliza',
                'nip' => '1234567890',
                'no_telepon' => '081234567890',
                'email' => 'siti.nurhaliza@example.com',
                'gambar_profil' => 'path/to/profile1.jpg',
                'tag_bidang_minat' => 1, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => 2, 
                'nama_lengkap' => 'Andi Saputra',
                'nip' => '2234567890',
                'no_telepon' => '082345678901',
                'email' => 'andi.saputra@example.com',
                'gambar_profil' => 'path/to/profile2.jpg',
                'tag_bidang_minat' => 2, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => 3, 
                'nama_lengkap' => 'Budi Santoso',
                'nip' => '3234567890',
                'no_telepon' => '083456789012',
                'email' => 'budi.santoso@example.com',
                'gambar_profil' => 'path/to/profile3.jpg',
                'tag_bidang_minat' => 3, 
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}