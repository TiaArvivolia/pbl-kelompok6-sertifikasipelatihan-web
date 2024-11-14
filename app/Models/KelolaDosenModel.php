<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelolaDosenModel extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'dosen';

    // Define the primary key for the model
    protected $primaryKey = 'id_dosen';

    // Define the fillable attributes
    protected $fillable = [
        'id_pengguna',
        'nama_lengkap',
        'nip',
        'nidn',
        'tempat_lahir',
        'tanggal_lahir',
        'no_telepon',
        'email',
        'gambar_profil',
        'tag_mk',
        'tag_bidang_minat',
    ];

    // Define the relationship with the 'pengguna' table
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    // Define the relationship with the 'mata_kuliah' table
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliahModel::class, 'tag_mk', 'id_mata_kuliah');
    }

    // Define the relationship with the 'bidang_minat' table
    public function bidangMinat()
    {
        return $this->belongsTo(BidangMinatModel::class, 'tag_bidang_minat', 'id_bidang_minat');
    }

    // Relasi polymorphic ke pengguna
    public function penggunaDosen()
    {
        return $this->morphOne(Pengguna::class, 'userable');
    }
}
