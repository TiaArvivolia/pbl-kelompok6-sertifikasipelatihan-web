<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelolaPimpinanModel extends Model
{
    use HasFactory;

    // Define the table name explicitly (optional if model name matches table)
    protected $table = 'pimpinan';

    // Define the primary key (optional if 'id' is primary key)
    protected $primaryKey = 'id_pimpinan';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'id_pengguna',
        'nama_lengkap',
        'nip',
        'nidn',
        'no_telepon',
        'email',
        'gambar_profil'
    ];

    /**
     * Define the relationship with the Pengguna model
     */
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }
}