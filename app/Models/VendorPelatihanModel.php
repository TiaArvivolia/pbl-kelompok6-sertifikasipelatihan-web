<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class VendorPelatihanModel extends Authenticatable
{
    protected $table = 'vendor_pelatihan';
    protected $primaryKey = 'id_vendor_pelatihan';

    protected $fillable = [
        'id_vendor_pelatihan',
        'nama',
        'alamat',
        'kota',
        'no_telepon',
        'website'
    ];

}