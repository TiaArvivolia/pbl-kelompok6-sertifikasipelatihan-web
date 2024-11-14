<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RiwayatPelatihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('riwayat_pelatihan')->insert([
            [
                'id_pengguna' => 1, // adjust based on existing id_pengguna in pengguna table
                'id_pelatihan' => null, // Assuming the training ID is not registered
                'level_pelatihan' => 'Nasional',
                'nama_pelatihan' => 'Pelatihan Data Analysis dengan Python',
                'tanggal_mulai' => Carbon::parse('2024-02-10'),
                'tanggal_selesai' => Carbon::parse('2024-02-15'),
                'lokasi' => 'Jakarta',
                'penyelenggara' => 'Data Academy',
                'dokumen_pelatihan' => 'certificate_data_analysis_python.pdf',
                'tag_mk' => 2, // adjust based on existing id_mata_kuliah
                'tag_bidang_minat' => 1, // adjust based on existing id_bidang_minat
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => 2,
                'id_pelatihan' => null,
                'level_pelatihan' => 'Internasional',
                'nama_pelatihan' => 'Certified Cloud Practitioner',
                'tanggal_mulai' => Carbon::parse('2024-03-05'),
                'tanggal_selesai' => Carbon::parse('2024-03-10'),
                'lokasi' => 'Online',
                'penyelenggara' => 'Amazon Web Services',
                'dokumen_pelatihan' => 'certificate_cloud_practitioner.pdf',
                'tag_mk' => 4,
                'tag_bidang_minat' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => 3,
                'id_pelatihan' => null,
                'level_pelatihan' => 'Nasional',
                'nama_pelatihan' => 'Pengembangan Aplikasi Mobile dengan Flutter',
                'tanggal_mulai' => Carbon::parse('2024-04-20'),
                'tanggal_selesai' => Carbon::parse('2024-04-25'),
                'lokasi' => 'Bandung',
                'penyelenggara' => 'TeknoDev',
                'dokumen_pelatihan' => 'certificate_flutter_dev.pdf',
                'tag_mk' => 3,
                'tag_bidang_minat' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}