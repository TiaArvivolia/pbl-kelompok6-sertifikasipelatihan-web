<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidangMinatModel extends Model
{
    use HasFactory;

    protected $table = 'bidang_minat';  // Specify the table name
    protected $primaryKey = 'id_bidang_minat';  // Specify the primary key if not 'id'

    protected $fillable = [
        'id_bidang_minat',
        'kode_bidang_minat',
        'nama_bidang_minat'
    ];

    // Relasi ke tabel sertifikasi
    public function sertifikasi()
    {
        return $this->hasMany(SertifikasiModel::class, 'tag_bidang_minat', 'id_bidang_minat');
    }
}