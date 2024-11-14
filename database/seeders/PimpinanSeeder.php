<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PimpinanSeeder extends Seeder
{
    public function run()
    {
        DB::table('pimpinan')->insert([
            [
                'id_pengguna' => 1, // Sesuaikan dengan data yang sudah ada di tabel 'pengguna'
                'nama_lengkap' => 'Dr. Budi Santoso',
                'nip' => '1985071212345678',
                'nidn' => '1234567890',
                'no_telepon' => '081234567890',
                'email' => 'budi.santoso@example.com',
                'gambar_profil' => 'profile_budi.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => 2,
                'nama_lengkap' => 'Dr. Siti Aminah',
                'nip' => '1986071212345678',
                'nidn' => '0987654321',
                'no_telepon' => '081234567891',
                'email' => 'siti.aminah@example.com',
                'gambar_profil' => 'profile_siti.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => 3,
                'nama_lengkap' => 'Prof. Andi Rahman',
                'nip' => '1975071212345678',
                'nidn' => '1122334455',
                'no_telepon' => '081234567892',
                'email' => 'andi.rahman@example.com',
                'gambar_profil' => 'profile_andi.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Tambahkan data lainnya sesuai kebutuhan
        ]);
    }
}
