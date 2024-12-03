<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdPeriodeToRiwayatSertifikasiAndPengajuanPelatihan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Menambahkan kolom id_periode ke tabel riwayat_sertifikasi
        Schema::table('riwayat_sertifikasi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_periode')->nullable(); // Menambahkan kolom id_periode
            $table->foreign('id_periode')->references('id_periode')->on('periode')->onDelete('cascade'); // Relasi ke tabel periode
        });

        // Menambahkan kolom id_periode ke tabel pengajuan_pelatihan
        Schema::table('pengajuan_pelatihan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_periode')->nullable(); // Menambahkan kolom id_periode
            $table->foreign('id_periode')->references('id_periode')->on('periode')->onDelete('cascade'); // Relasi ke tabel periode
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Menghapus kolom id_periode dari tabel riwayat_sertifikasi
        Schema::table('riwayat_sertifikasi', function (Blueprint $table) {
            $table->dropForeign(['id_periode']); // Menghapus foreign key
            $table->dropColumn('id_periode'); // Menghapus kolom id_periode
        });

        // Menghapus kolom id_periode dari tabel pengajuan_pelatihan
        Schema::table('pengajuan_pelatihan', function (Blueprint $table) {
            $table->dropForeign(['id_periode']); // Menghapus foreign key
            $table->dropColumn('id_periode'); // Menghapus kolom id_periode
        });
    }
}