<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DosenSeeder extends Seeder
{
    public function run()
    {
        DB::table('dosen')->insert([
            [
                'id_pengguna' => 1, // Replace with actual id_pengguna from pengguna table
                'nama_lengkap' => 'Dr. Budi Santoso',
                'nip' => '1234567890123456',
                'nidn' => '9876543210',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => Carbon::parse('1970-01-15'),
                'no_telepon' => '081234567890',
                'email' => 'budi.santoso@example.com',
                'gambar_profil' => 'path/to/profile1.jpg', // Optional: Add path to profile image
                'tag_mk' => 1, // Replace with actual id_mata_kuliah from mata_kuliah table
                'tag_bidang_minat' => 2, // Replace with actual id_bidang_minat from bidang_minat table
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => 2,
                'nama_lengkap' => 'Prof. Ani Wijaya',
                'nip' => '2234567890123456',
                'nidn' => '9876543211',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => Carbon::parse('1975-03-21'),
                'no_telepon' => '082345678901',
                'email' => 'ani.wijaya@example.com',
                'gambar_profil' => 'path/to/profile2.jpg',
                'tag_mk' => 2,
                'tag_bidang_minat' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => 3,
                'nama_lengkap' => 'Dr. Iwan Setiawan',
                'nip' => '3234567890123456',
                'nidn' => '9876543212',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => Carbon::parse('1980-05-10'),
                'no_telepon' => '083456789012',
                'email' => 'iwan.setiawan@example.com',
                'gambar_profil' => 'path/to/profile3.jpg',
                'tag_mk' => 3,
                'tag_bidang_minat' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}