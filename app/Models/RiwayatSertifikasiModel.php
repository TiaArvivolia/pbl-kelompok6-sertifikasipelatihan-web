<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatSertifikasiModel extends Model
{
    use HasFactory;

    // Define the table name (optional if it follows the default naming convention)
    protected $table = 'riwayat_sertifikasi';

    // Define the primary key for the model (optional if it follows the default 'id')
    protected $primaryKey = 'id_riwayat';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'id_pengguna',
        'id_pelatihan',
        'level_sertifikasi',
        'diselenggarakan_oleh',
        'jenis_sertifikasi',
        'nama_sertifikasi',
        'no_sertifikat',
        'tanggal_terbit',
        'masa_berlaku',
        'penyelenggara',
        'dokumen_sertifikat',
        'tag_mk',
        'tag_bidang_minat',
    ];

    // Define the relationship to the Pengguna model
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    // Define the relationship to the DaftarPelatihan model
    public function daftarPelatihan()
    {
        return $this->belongsTo(DaftarPelatihanModel::class, 'id_pelatihan', 'id_pelatihan');
    }

    // Define the relationship to the VendorSertifikasi model (penyelenggara)
    // public function penyelenggara()
    // {
    //     return $this->belongsTo(VendorSertifikasiModel::class, 'penyelenggara', 'id_vendor_sertifikasi');
    // }

    // Define the relationship to the MataKuliah model
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliahModel::class, 'tag_mk', 'id_mata_kuliah');
    }

    // Define the relationship to the BidangMinat model
    public function bidangMinat()
    {
        return $this->belongsTo(BidangMinatModel::class, 'tag_bidang_minat', 'id_bidang_minat');
    }
}