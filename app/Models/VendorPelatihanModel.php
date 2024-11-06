<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class VendorPelatihanModel extends Authenticatable
{
    protected $table = 'vendorpelatihan';
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
