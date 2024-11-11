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
        Schema::create('draft_surat_tugas_pelatihan', function (Blueprint $table) {
            $table->id('id_draft'); // Create the primary key
            // Foreign key for 'id_pengajuan' referencing 'id_pelatihan' in the 'pengajuan_pelatihan' table
            $table->bigInteger('id_pengajuan')->unsigned(); 
            $table->foreign('id_pengajuan')->references('id_pengajuan')->on('pengajuan_pelatihan')->onDelete('cascade');
            // Foreign key for 'id_pengguna' referencing 'id_pengguna' in the 'pengguna' table
            $table->bigInteger('id_pengguna')->unsigned(); 
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
            $table->date('tanggal_ditugaskan');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('lokasi_pelatihan', 255)->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draft_surat_tugas_pelatihan');
    }
};