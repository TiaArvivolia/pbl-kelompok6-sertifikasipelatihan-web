<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPenggunaModel extends Model
{
    use HasFactory;

    // Menentukan nama tabel
    protected $table = 'jenis_pengguna';

    // Menentukan primary key
    protected $primaryKey = 'id_jenis_pengguna';

    // Menentukan atribut yang dapat diisi secara mass-assignment
    protected $fillable = [
        'kode_jenis_pengguna',
        'nama_jenis_pengguna',
    ];

    // Menentukan apakah kita ingin timestamps otomatis
    public $timestamps = true;
}