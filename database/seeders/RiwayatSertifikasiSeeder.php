<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiwayatSertifikasiModel;
use App\Models\Pengguna;
use App\Models\DaftarPelatihanModel;
use App\Models\VendorSertifikasiModel;
use App\Models\MataKuliahModel;
use App\Models\BidangMinatModel;

class RiwayatSertifikasiSeeder extends Seeder
{
    public function run()
    {
        // Ambil data pengguna dan ID lainnya yang diperlukan
        $pengguna = Pengguna::pluck('id_pengguna')->toArray();
        $daftarPelatihan = DaftarPelatihanModel::pluck('id_pelatihan')->toArray();
        $penyelenggara = VendorSertifikasiModel::pluck('id_vendor_sertifikasi')->toArray();
        $mataKuliah = MataKuliahModel::pluck('id_mata_kuliah')->toArray();
        $bidangMinat = BidangMinatModel::pluck('id_bidang_minat')->toArray();

        // Sertifikasi IT yang akan dimasukkan
        $sertifikasiIT = [
            [
                'level_sertifikasi' => 'Nasional',
                'diselenggarakan_oleh' => 'Mandiri',
                'jenis_sertifikasi' => 'Profesi',
                'nama_sertifikasi' => 'Certified Information Systems Security Professional (CISSP)',
                'no_sertifikat' => 'CISSP-2024001',
                'tanggal_terbit' => '2024-01-01',
                'masa_berlaku' => '2027-01-01',
                'tag_mk' => $mataKuliah[0] ?? null, // ID mata kuliah terkait
                'tag_bidang_minat' => $bidangMinat[0] ?? null, // ID bidang minat terkait
            ],
            [
                'level_sertifikasi' => 'Internasional',
                'diselenggarakan_oleh' => 'Ikut Pelatihan',
                'jenis_sertifikasi' => 'Keahlian',
                'nama_sertifikasi' => 'Certified Ethical Hacker (CEH)',
                'no_sertifikat' => 'CEH-2024002',
                'tanggal_terbit' => '2024-02-15',
                'masa_berlaku' => '2026-02-15',
                'tag_mk' => $mataKuliah[1] ?? null, // ID mata kuliah terkait
                'tag_bidang_minat' => $bidangMinat[1] ?? null, // ID bidang minat terkait
            ],
            [
                'level_sertifikasi' => 'Nasional',
                'diselenggarakan_oleh' => 'Mandiri',
                'jenis_sertifikasi' => 'Profesi',
                'nama_sertifikasi' => 'Cisco Certified Network Associate (CCNA)',
                'no_sertifikat' => 'CCNA-2024003',
                'tanggal_terbit' => '2024-03-01',
                'masa_berlaku' => '2026-03-01',
                'tag_mk' => $mataKuliah[2] ?? null, // ID mata kuliah terkait
                'tag_bidang_minat' => $bidangMinat[2] ?? null, // ID bidang minat terkait
            ],
            [
                'level_sertifikasi' => 'Internasional',
                'diselenggarakan_oleh' => 'Ikut Pelatihan',
                'jenis_sertifikasi' => 'Profesi',
                'nama_sertifikasi' => 'AWS Certified Solutions Architect – Associate',
                'no_sertifikat' => 'AWS-2024004',
                'tanggal_terbit' => '2024-04-20',
                'masa_berlaku' => '2027-04-20',
                'tag_mk' => $mataKuliah[3] ?? null, // ID mata kuliah terkait
                'tag_bidang_minat' => $bidangMinat[3] ?? null, // ID bidang minat terkait
            ],
        ];

        // Menambahkan sertifikasi ke dalam tabel riwayat_sertifikasi
        foreach ($sertifikasiIT as $sertifikasi) {
            RiwayatSertifikasiModel::create([
                'id_pengguna' => $pengguna[array_rand($pengguna)], // Menentukan pengguna secara acak
                'id_pelatihan' => $daftarPelatihan[array_rand($daftarPelatihan)] ?? null, // Pelatihan opsional
                'level_sertifikasi' => $sertifikasi['level_sertifikasi'],
                'diselenggarakan_oleh' => $sertifikasi['diselenggarakan_oleh'],
                'jenis_sertifikasi' => $sertifikasi['jenis_sertifikasi'],
                'nama_sertifikasi' => $sertifikasi['nama_sertifikasi'],
                'no_sertifikat' => $sertifikasi['no_sertifikat'],
                'tanggal_terbit' => $sertifikasi['tanggal_terbit'],
                'masa_berlaku' => $sertifikasi['masa_berlaku'],
                'penyelenggara' => $penyelenggara[array_rand($penyelenggara)] ?? null, // Penyelenggara opsional
                'dokumen_sertifikat' => null, // Kosongkan jika tidak ada dokumen
                'tag_mk' => $sertifikasi['tag_mk'],
                'tag_bidang_minat' => $sertifikasi['tag_bidang_minat'],
            ]);
        }
    }
}