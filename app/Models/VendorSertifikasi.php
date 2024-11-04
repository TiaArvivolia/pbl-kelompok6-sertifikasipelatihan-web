<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class VendorSertifikasi extends Authenticatable
{
    protected $table = 'vendorsertifikasi';
    protected $primaryKey = 'id_vendor_sertifikasi';

    protected $fillable = [
        'id_vendor_sertifikasi',
        'nama',
        'alamat',
        'kota',
        'no_telepon',
        'website'
    ];

}
