<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPenggunaModel extends Model
{
    use HasFactory;

    protected $table = 'jenis_pengguna';

    protected $primaryKey = 'id_jenis_pengguna';

    protected $fillable = [
        'kode_jenis_pengguna',
        'nama_jenis_pengguna',
    ];

       // Relasi ke pengguna
       public function pengguna()
       {
           return $this->hasMany(Pengguna::class, 'id_jenis_pengguna');
       }

    public $timestamps = true;
}