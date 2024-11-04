<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run()
    {
        DB::table('pengguna')->insert([
            [
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'nama_lengkap' => 'Admin User',
                'nip' => '123456789',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1980-01-01',
                'jenis_kelamin' => 'L',
                'no_telepon' => '081234567890',
                'nik' => '3201234567890001',
                'nidn' => '123456789',
                'agama' => 'Islam',
                'alamat' => 'Jl. Admin No. 1',
                'email' => 'admin@example.com',
                'peran' => 'Admin',
                'photo_profile' => 'admin.jpg'
            ],
            [
                'username' => 'dosen1',
                'password' => Hash::make('password123'),
                'nama_lengkap' => 'Dosen Pertama',
                'nip' => '987654321',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1975-05-15',
                'jenis_kelamin' => 'L',
                'no_telepon' => '081987654321',
                'nik' => '3209876543210002',
                'nidn' => '987654321',
                'agama' => 'Kristen',
                'alamat' => 'Jl. Dosen No. 2',
                'email' => 'dosen1@example.com',
                'peran' => 'Dosen',
                'photo_profile' => 'dosen1.jpg'
            ],
            [
                'username' => 'pimpinan1',
                'password' => Hash::make('password123'),
                'nama_lengkap' => 'Pimpinan Utama',
                'nip' => '1122334455',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1965-12-20',
                'jenis_kelamin' => 'P',
                'no_telepon' => '081223344556',
                'nik' => '3201122334455003',
                'nidn' => '1122334455',
                'agama' => 'Hindu',
                'alamat' => 'Jl. Pimpinan No. 3',
                'email' => 'pimpinan1@example.com',
                'peran' => 'Pimpinan',
                'photo_profile' => 'pimpinan1.jpg'
            ]
        ]);

    }
}