<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';

    protected $fillable = [
        'username',
        'password',
        'nama_lengkap',
        'nip',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'no_telepon',
        'nik',
        'nidn',
        'agama',
        'alamat',
        'email',
        'peran',
        'photo_profile'
    ];

    protected $hidden = [
        'password'
    ];

    public $timestamps = false;
}
