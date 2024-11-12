<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run()
    {
        DB::table('pengguna')->insert([
            [
                'username' => 'admin_user',
                'password' => Hash::make('password123'), // Password hashing for security
                'id_jenis_pengguna' => 1, // Assuming 1 is the id for Admin in jenis_pengguna table
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'username' => 'dosen_user',
                'password' => Hash::make('password123'),
                'id_jenis_pengguna' => 2, // Assuming 2 is the id for Dosen
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'username' => 'tendik_user',
                'password' => Hash::make('password123'),
                'id_jenis_pengguna' => 3, // Assuming 3 is the id for Tendik
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'username' => 'pimpinan_user',
                'password' => Hash::make('password123'),
                'id_jenis_pengguna' => 4, // Assuming 4 is the id for Pimpinan
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}