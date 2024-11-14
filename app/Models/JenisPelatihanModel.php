<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPelatihanModel extends Model
{
    use HasFactory;

    protected $table = 'jenis_pelatihan';

    protected $primaryKey = 'id_jenis_pelatihan';

    protected $fillable = [
        'nama_jenis_pelatihan',
    ];

    public $timestamps = true;
}