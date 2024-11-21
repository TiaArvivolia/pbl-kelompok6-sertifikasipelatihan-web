<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengguna extends Authenticatable
{
    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';

    protected $guarded = [
        'web' => [
            'driver' => 'session',
            'provider' => 'pengguna',
        ],
    ];

    // Define only the columns that are present in the migration
    protected $fillable = [
        'username',
        'password',
        'id_jenis_pengguna',
    ];

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
        return $this->hasOne(KelolaDosenModel::class, 'id_pengguna');
    }

    // Relasi dengan tendik (jika ada)
    public function tendik()
    {
        return $this->hasOne(KelolaTendikModel::class, 'id_pengguna');
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
}
