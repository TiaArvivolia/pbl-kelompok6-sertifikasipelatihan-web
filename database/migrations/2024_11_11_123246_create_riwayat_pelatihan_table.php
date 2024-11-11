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
        Schema::create('riwayat_pelatihan', function (Blueprint $table) {
            $table->id('id_riwayat');

            // Foreign key for id_pengguna referencing id_pengguna in pengguna table
            $table->bigInteger('id_pengguna')->unsigned(); 
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');

            // Foreign key for id_pelatihan referencing id_pelatihan in daftar_pelatihan table
            $table->bigInteger('id_pelatihan')->unsigned()->nullable(); 
            $table->foreign('id_pelatihan')->references('id_pelatihan')->on('daftar_pelatihan')->onDelete('set null');

            $table->enum('level_pelatihan', ['Nasional', 'Internasional']);
            $table->string('nama_pelatihan', 100);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('lokasi', 100)->nullable();
            $table->string('penyelenggara', 100)->nullable();
            $table->string('dokumen_pelatihan', 255)->nullable();

            // Foreign key for tag_mk referencing id_mata_kuliah in mata_kuliah table
            $table->bigInteger('tag_mk')->unsigned()->nullable(); 
            $table->foreign('tag_mk')->references('id_mata_kuliah')->on('mata_kuliah')->onDelete('set null');

            // Foreign key for tag_bidang_minat referencing id_bidang_minat in bidang_minat table
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
        Schema::dropIfExists('riwayat_pelatihan');
    }
};