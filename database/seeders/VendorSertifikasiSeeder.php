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
    public function run()
    {
        // Insert multiple records into the vendor_sertifikasi table
        DB::table('vendor_sertifikasi')->insert([
            [
                'nama' => 'PT. Sertifikasi Indonesia',
                'kota' => 'Jakarta',
                'alamat' => 'Jl. Jendral Sudirman No. 45, Jakarta Pusat',
                'no_telepon' => '021-1234567',
                'website' => 'https://www.sertifikasiindonesia.co.id',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Lembaga Sertifikasi Profesi',
                'kota' => 'Bandung',
                'alamat' => 'Jl. Merdeka No. 12, Bandung',
                'no_telepon' => '022-7654321',
                'website' => 'https://www.lsp.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Sertifikasi Expert',
                'kota' => 'Surabaya',
                'alamat' => 'Jl. Raya No. 8, Surabaya',
                'no_telepon' => '031-2345678',
                'website' => 'https://www.sertifikasi-expert.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Global Training Certification',
                'kota' => 'Yogyakarta',
                'alamat' => 'Jl. Pahlawan No. 7, Yogyakarta',
                'no_telepon' => '0274-9876543',
                'website' => 'https://www.globaltraining.com',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
