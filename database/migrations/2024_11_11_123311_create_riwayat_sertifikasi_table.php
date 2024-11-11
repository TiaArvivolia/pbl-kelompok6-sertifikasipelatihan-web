<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up()
    {
        Schema::create('riwayat_sertifikasi', function (Blueprint $table) {
            $table->id('id_riwayat');

            // Foreign Key to pengguna table
            $table->bigInteger('id_pengguna')->unsigned(); 
            $table->foreign('id_pengguna')
                ->references('id_pengguna')
                ->on('pengguna')
                ->onDelete('cascade'); // Optional: If a pengguna is deleted, related riwayat_sertifikasi will also be deleted

            // Foreign Key to daftar_pelatihan table
            $table->bigInteger('id_pelatihan')->unsigned()->nullable(); 
            $table->foreign('id_pelatihan')
                ->references('id_pelatihan')
                ->on('daftar_pelatihan')
                ->onDelete('set null'); // Optional: If a pelatihan is deleted, the id_pelatihan in riwayat_sertifikasi will be set to null

            $table->enum('level_sertifikasi', ['Nasional', 'Internasional']);
            $table->enum('diselenggarakan_oleh', ['Mandiri', 'Ikut Pelatihan']);
            $table->enum('jenis_sertifikasi', ['Profesi', 'Keahlian']);
            $table->string('nama_sertifikasi', 100);
            $table->string('no_sertifikat', 50);
            $table->date('tanggal_terbit');
            $table->date('masa_berlaku');
            $table->string('penyelenggara', 100);
            $table->string('dokumen_sertifikat', 255)->nullable();

            // Foreign Key to mata_kuliah table
            $table->bigInteger('tag_mk')->unsigned()->nullable(); 
            $table->foreign('tag_mk')
                ->references('id_mata_kuliah')
                ->on('mata_kuliah')
                ->onDelete('set null'); // Optional: If mata_kuliah is deleted, set tag_mk to null

            // Foreign Key to bidang_minat table
            $table->bigInteger('tag_bidang_minat')->unsigned()->nullable(); 
            $table->foreign('tag_bidang_minat')
                ->references('id_bidang_minat')
                ->on('bidang_minat')
                ->onDelete('set null'); // Optional: If bidang_minat is deleted, set tag_bidang_minat to null

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_sertifikasi');
    }
};