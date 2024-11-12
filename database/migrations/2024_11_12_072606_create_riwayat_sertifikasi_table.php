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

            // Foreign key to pengguna
            $table->bigInteger('id_pengguna')->unsigned();
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');

            // Foreign key to daftar_pelatihan (nullable)
            $table->bigInteger('id_pelatihan')->unsigned()->nullable();
            $table->foreign('id_pelatihan')->references('id_pelatihan')->on('daftar_pelatihan')->onDelete('set null');

            $table->enum('level_sertifikasi', ['Nasional', 'Internasional']);
            $table->enum('diselenggarakan_oleh', ['Mandiri', 'Ikut Pelatihan']);
            $table->enum('jenis_sertifikasi', ['Profesi', 'Keahlian']);
            $table->string('nama_sertifikasi')->nullable();
            $table->string('no_sertifikat');
            $table->date('tanggal_terbit');
            $table->date('masa_berlaku')->nullable();

            // Foreign key for penyelenggara, referencing id_vendor_sertifikasi in vendor_sertifikasi
            $table->bigInteger('penyelenggara')->unsigned()->nullable();
            $table->foreign('penyelenggara')->references('id_vendor_sertifikasi')->on('vendor_sertifikasi')->onDelete('cascade');

            $table->string('dokumen_sertifikat')->nullable();

            // Foreign key to mata_kuliah (nullable)
            $table->bigInteger('tag_mk')->unsigned()->nullable();
            $table->foreign('tag_mk')->references('id_mata_kuliah')->on('mata_kuliah')->onDelete('set null');

            // Foreign key to bidang_minat (nullable)
            $table->bigInteger('tag_bidang_minat')->unsigned()->nullable();
            $table->foreign('tag_bidang_minat')->references('id_bidang_minat')->on('bidang_minat')->onDelete('set null');

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
