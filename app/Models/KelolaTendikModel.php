<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelolaTendikModel extends Model
{
    use HasFactory;

    // Define the table name (optional if it follows Laravel's convention)
    protected $table = 'tendik';

    // Define the primary key
    protected $primaryKey = 'id_tendik';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'id_pengguna',
        'nama_lengkap',
        'nip',
        'no_telepon',
        'email',
        'gambar_profil',
        'tag_bidang_minat'
    ];

    // Define the relationship with the Pengguna model
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    // Define the relationship with the BidangMinat model
    public function bidangMinat()
    {
        return $this->belongsTo(BidangMinatModel::class, 'tag_bidang_minat', 'id_bidang_minat');
    }

    // Relasi polymorphic ke pengguna
    public function penggunaTendik()
    {
        return $this->morphOne(Pengguna::class, 'userable');
    }
}