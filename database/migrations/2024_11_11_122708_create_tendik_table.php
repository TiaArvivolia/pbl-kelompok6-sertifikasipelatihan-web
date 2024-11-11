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
        Schema::create('tendik', function (Blueprint $table) {
            $table->id('id_tendik');
            // Foreign key for 'id_pengguna' referencing 'id_pengguna' in 'pengguna' table
            $table->bigInteger('id_pengguna')->unsigned();
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
            $table->string('nama_lengkap', 100);
            $table->string('nip', 20);
            $table->string('no_telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('gambar_profil', 255)->nullable();
            // Foreign key for 'tag_bidang_minat' referencing 'id_bidang_minat' in 'bidang_minat' table
            $table->bigInteger('tag_bidang_minat')->unsigned()->nullable(); // Assuming nullable for optional foreign key
            $table->foreign('tag_bidang_minat')->references('id_bidang_minat')->on('bidang_minat')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tendik');
    }
};