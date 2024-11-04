<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah';  // Specify the table name
    protected $primaryKey = 'id_mata_kuliah';  // Specify the primary key if not 'id'
    
    protected $fillable = [
        'id_mata_kuliah',
        'kode_mk',
        'nama_mk'
    ];
}
