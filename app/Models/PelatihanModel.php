<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelatihanModel extends Model
{
    use HasFactory;

    protected $table = 'riwayat_pelatihan'; // Name of your table in the database

    protected $primaryKey = 'id_riwayat';

    // Define the fields that are mass assignable
    protected $fillable = [
        'id_dosen',
        'id_pelatihan',
        'nama_pelatihan',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'penyelenggara',
        'level_pelatihan',
        'dokumen_pelatihan',
        'tag_mk',
        'tag_bidang_minat'
       
    ];

    // Accessor for document URL if stored in public disk
    public function getDokumenSertifikatUrlAttribute()
    {
        return $this->dokumen_sertifikat ? asset('storage/' . $this->dokumen_sertifikat) : null;
    }
}
