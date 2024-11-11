<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SertifikasiModel extends Model
{
    use HasFactory;

    protected $table = 'riwayat_sertifikasi'; // Name of your table in the database

    protected $primaryKey = 'id_riwayat';

    // Define the fields that are mass assignable
    protected $fillable = [
        'id_dosen',
        'id_sertifikasi',
        'nama_sertifikasi',
        'no_sertifikat',
        'jenis_sertifikasi',
        'tanggal_terbit',
        'masa_berlaku',
        'penyelenggara',
        'dokumen_sertifikat',
        'diselenggarakan_oleh',
        'tag_mk',
        'tag_bidang_minat',
    ];

    // Accessor for document URL if stored in public disk
    public function getDokumenSertifikatUrlAttribute()
    {
        return $this->dokumen_sertifikat ? asset('storage/' . $this->dokumen_sertifikat) : null;
    }
}
