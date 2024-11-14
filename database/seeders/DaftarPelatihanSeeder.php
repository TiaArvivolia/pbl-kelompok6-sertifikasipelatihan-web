<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DaftarPelatihanSeeder extends Seeder
{
    public function run()
    {
        DB::table('daftar_pelatihan')->insert([
            [
                'level_pelatihan' => 'Nasional',
                'nama_pelatihan' => 'Pelatihan Pengembangan Aplikasi Web dengan Laravel',
                'tanggal_mulai' => Carbon::create(2024, 1, 15),
                'tanggal_selesai' => Carbon::create(2024, 1, 17),
                'kuota' => 30,
                'lokasi' => 'Jakarta, Indonesia',
                'biaya' => 2000000.00,
                'jml_jam' => 24,
                'id_vendor_pelatihan' => 1, // Sesuaikan dengan ID vendor yang ada di tabel 'vendor_pelatihan'
                'tag_mk' => 1, // Sesuaikan dengan ID mata kuliah yang ada di tabel 'mata_kuliah'
                'tag_bidang_minat' => 1, // Sesuaikan dengan ID bidang minat yang ada di tabel 'bidang_minat'
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level_pelatihan' => 'Internasional',
                'nama_pelatihan' => 'International Seminar on Cybersecurity and Data Privacy',
                'tanggal_mulai' => Carbon::create(2024, 3, 5),
                'tanggal_selesai' => Carbon::create(2024, 3, 7),
                'kuota' => 50,
                'lokasi' => 'Online',
                'biaya' => 3000000.00,
                'jml_jam' => 16,
                'id_vendor_pelatihan' => 2, // Sesuaikan dengan ID vendor yang ada di tabel 'vendor_pelatihan'
                'tag_mk' => 2, // Sesuaikan dengan ID mata kuliah yang ada di tabel 'mata_kuliah'
                'tag_bidang_minat' => 1, // Sesuaikan dengan ID bidang minat yang ada di tabel 'bidang_minat'
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level_pelatihan' => 'Nasional',
                'nama_pelatihan' => 'Pelatihan Big Data dan Analisis untuk Pengembangan Bisnis',
                'tanggal_mulai' => Carbon::create(2024, 4, 10),
                'tanggal_selesai' => Carbon::create(2024, 4, 12),
                'kuota' => 40,
                'lokasi' => 'Bandung, Indonesia',
                'biaya' => 2500000.00,
                'jml_jam' => 20,
                'id_vendor_pelatihan' => 3, // Sesuaikan dengan ID vendor yang ada di tabel 'vendor_pelatihan'
                'tag_mk' => 3, // Sesuaikan dengan ID mata kuliah yang ada di tabel 'mata_kuliah'
                'tag_bidang_minat' => 1, // Sesuaikan dengan ID bidang minat yang ada di tabel 'bidang_minat'
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Tambahkan pelatihan lainnya jika diperlukan
        ]);
    }
}
