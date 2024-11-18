<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdPesertaToPengajuanPelatihan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengajuan_pelatihan', function (Blueprint $table) {
            $table->json('id_peserta')->nullable(); // Menambahkan kolom JSON untuk menyimpan ID peserta
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pengajuan_pelatihan', function (Blueprint $table) {
            $table->dropColumn('id_peserta'); // Menghapus kolom id_peserta
        });
    }
}