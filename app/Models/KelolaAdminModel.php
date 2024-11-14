<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelolaAdminModel extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'admin';

    // Define the primary key for the model
    protected $primaryKey = 'id_admin';

    // Define the fillable attributes
    protected $fillable = [
        'id_pengguna',
        'nama_lengkap',
        'nip',
        'no_telepon',
        'email',
        'gambar_profil',
    ];

    // Define the relationship with the 'pengguna' table
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}