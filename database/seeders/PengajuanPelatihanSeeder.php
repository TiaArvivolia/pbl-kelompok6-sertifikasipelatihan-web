<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengajuanPelatihanSeeder extends Seeder
{
    public function run()
    {
        // Insert pengajuan pelatihan dengan peserta lebih dari satu
        DB::table('pengajuan_pelatihan')->insert([
            [
                'id_pengguna' => 2, // ID pengguna untuk pengajuan
                'id_pelatihan' => 2, // ID pelatihan
                'tanggal_pengajuan' => Carbon::now()->toDateString(),
                'status' => 'Menunggu',
                'catatan' => 'Pengajuan pelatihan untuk peserta 2 dan 8',
                'id_peserta' => json_encode([2, 8]), // Menyimpan array ID peserta
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}