<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPelatihanModel extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional, as Laravel will automatically guess it)
    protected $table = 'pengajuan_pelatihan';

    // Define the primary key (optional, as Laravel will automatically guess 'id' is the primary key)
    protected $primaryKey = 'id_pengajuan';

    // Define the fields that can be mass-assigned
    protected $fillable = [
        'id_pengguna', 
        'id_pelatihan', 
        'tanggal_pengajuan', 
        'status', 
        'catatan'
    ];

    // Specify the date format if needed
    protected $dates = ['tanggal_pengajuan'];

    // Define the relationships with the other models

    // Relationship with the 'Pengguna' model (foreign key: id_pengguna)
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    // Relationship with the 'DaftarPelatihan' model (foreign key: id_pelatihan)
    public function daftarPelatihan()
    {
        return $this->belongsTo(DaftarPelatihanModel::class, 'id_pelatihan', 'id_pelatihan');
    }
}