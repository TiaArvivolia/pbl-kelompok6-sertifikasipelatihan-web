<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPelatihanModel extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'riwayat_pelatihan';

    // Define the primary key
    protected $primaryKey = 'id_riwayat';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'id_pengguna',
        'id_pelatihan',
        'level_pelatihan',
        'nama_pelatihan',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'penyelenggara',
        'dokumen_pelatihan',
        'mk_list',
        'bidang_minat_list',
        'id_periode'
    ];

    // Define relationships
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    public function daftarPelatihan()
    {
        return $this->belongsTo(DaftarPelatihanModel::class, 'id_pelatihan');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliahModel::class, 'tag_mk', 'id_mata_kuliah');
    }

    public function bidangMinat()
    {
        return $this->belongsTo(BidangMinatModel::class, 'tag_bidang_minat', 'id_bidang_minat');
    }

    // Definisikan relasi ke model VendorPelatihan
    public function penyelenggara()
    {
        return $this->belongsTo(VendorPelatihanModel::class, 'penyelenggara', 'id_vendor_pelatihan');
    }
    
    public function periode()
    {
        return $this->belongsTo(PeriodeModel::class, 'id_periode', 'id_periode');
    }
}