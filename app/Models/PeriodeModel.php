<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeModel extends Model
{
    use HasFactory;

    protected $table = 'periode';  // Specify the table name
    protected $primaryKey = 'id_periode';  // Specify the primary key if not 'id'

    protected $fillable = [
        'tahun_periode'
    ];


    /**
     * Define the relationship to related tables.
     */
    public function riwayatPelatihan()
    {
        return $this->hasMany(RiwayatPelatihanModel::class, 'id_periode', 'id_periode');
    }

    public function riwayatSertifikasi()
    {
        return $this->hasMany(RiwayatSertifikasiModel::class, 'id_periode', 'id_periode');
    }

    public function pengajuanPelatihan()
    {
        return $this->hasMany(PengajuanPelatihanModel::class, 'id_periode', 'id_periode');
    }
}