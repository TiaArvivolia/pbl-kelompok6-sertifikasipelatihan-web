<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Pengguna extends Authenticatable implements JWTSubject
{
    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';

    // Define only the columns that are present in the migration
    protected $fillable = [
        'username',
        'password',
        'id_jenis_pengguna',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }


    protected $hidden = [
        'password'
    ];

    public $timestamps = true;

    /**
     * Define the relationship with the JenisPengguna model.
     */
    public function jenisPengguna(): BelongsTo
    {
        return $this->belongsTo(JenisPenggunaModel::class, 'id_jenis_pengguna', 'id_jenis_pengguna');
    }

    // Relasi dengan dosen
    public function dosen()
    {
        return $this->hasOne(KelolaDosenModel::class, 'id_pengguna', 'id_pengguna');
    }

    // Relasi dengan tendik (jika ada)
    public function tendik()
    {
        return $this->hasOne(KelolaTendikModel::class, 'id_pengguna', 'id_pengguna');
    }

    // Relasi dengan admin
    public function admin()
    {
        return $this->hasOne(KelolaAdminModel::class, 'id_pengguna');
    }

    // Relasi dengan pimpinan
    public function pimpinan()
    {
        return $this->hasOne(KelolaPimpinanModel::class, 'id_pengguna');
    }

    // Define the relationship with RiwayatPelatihan
    public function riwayatPelatihan()
    {
        return $this->hasMany(RiwayatPelatihanModel::class, 'id_pengguna', 'id_pengguna');
    }

    // Define the relationship with RiwayatSertifikasi
    public function riwayatSertifikasi()
    {
        return $this->hasMany(RiwayatSertifikasiModel::class, 'id_pengguna', 'id_pengguna');
    }
}
