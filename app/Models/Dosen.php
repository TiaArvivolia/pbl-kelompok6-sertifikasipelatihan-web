<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'dosen';

    // Primary key dari tabel
    protected $primaryKey = 'id_dosen';

    // Primary key menggunakan tipe data non-integer (bigint)
    public $incrementing = true;
    protected $keyType = 'int'; // Laravel menganggap bigint sama dengan integer

    // Kolom-kolom yang bisa diisi (mass-assignable)
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
        'mk_list',
        'bidang_minat_list',
    ];

    // Kolom dengan format tanggal
    protected $dates = ['tanggal_lahir', 'created_at', 'updated_at'];

    // Konversi otomatis untuk kolom JSON
    protected $casts = [
        'mk_list' => 'array',
        'bidang_minat_list' => 'array',
    ];
}