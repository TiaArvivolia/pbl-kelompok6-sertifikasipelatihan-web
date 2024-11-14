<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Insert sample data into the admin table
        DB::table('admin')->insert([
            [
                'id_pengguna' => 1, // Assuming the id_pengguna 1 corresponds to an Admin in the pengguna table
                'nama_lengkap' => 'John Doe',
                'nip' => '1234567890',
                'no_telepon' => '081234567890',
                'email' => 'johndoe@example.com',
                'gambar_profil' => 'profile_picture_1.jpg', // You can replace with a real image file name
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_pengguna' => 2, // Assuming the id_pengguna 2 corresponds to another Admin
                'nama_lengkap' => 'Jane Smith',
                'nip' => '0987654321',
                'no_telepon' => '082345678901',
                'email' => 'janesmith@example.com',
                'gambar_profil' => 'profile_picture_2.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
