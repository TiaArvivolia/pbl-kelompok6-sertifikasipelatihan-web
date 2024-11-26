<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarPelatihanModel extends Model
{
    use HasFactory;

    protected $table = 'daftar_pelatihan';

    protected $primaryKey = 'id_pelatihan';

    // Menentukan kolom-kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'level_pelatihan',
        'nama_pelatihan',
        'tanggal_mulai',
        'tanggal_selesai',
        'kuota',
        'lokasi',
        'biaya',
        'jml_jam',
        'id_vendor_pelatihan',
        'mk_list',
        'bidang_minat_list',
    ];

    // Relasi ke tabel 'vendor_pelatihan'
    public function vendorPelatihan()
    {
        return $this->belongsTo(VendorPelatihanModel::class, 'id_vendor_pelatihan');
    }

    // Relasi ke tabel 'mata_kuliah'
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliahModel::class, 'tag_mk', 'id_mata_kuliah');
    }

    // Relasi ke tabel 'bidang_minat'
    public function bidangMinat()
    {
        return $this->belongsTo(BidangMinatModel::class, 'tag_bidang_minat', 'id_bidang_minat');
    }

    // Relationship with the 'pengajuan' model (foreign key: id_pelatihan)
    public function pengajuan()
    {
        return $this->hasOne(PengajuanPelatihanModel::class, 'id_pelatihan', 'id_pelatihan');
    }
}