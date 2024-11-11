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
        Schema::create('dosen', function (Blueprint $table) {
            $table->id('id_dosen');

            // Foreign key for id_pengguna, referencing id_pengguna in the pengguna table
            $table->bigInteger('id_pengguna')->unsigned();
            $table->foreign('id_pengguna')
                ->references('id_pengguna')
                ->on('pengguna')
                ->onDelete('cascade'); // This ensures that deleting a pengguna record will also delete related dosen records

            $table->string('nama_lengkap', 100);
            $table->string('nip', 20);
            $table->string('nidn', 20);
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('gambar_profil', 255)->nullable();

            // Foreign key for tag_mk, referencing id_mata_kuliah in the mata_kuliah table
            $table->bigInteger('tag_mk')->unsigned()->nullable(); // Assuming nullable for optional foreign key
            $table->foreign('tag_mk')
                ->references('id_mata_kuliah')
                ->on('mata_kuliah')
                ->onDelete('set null'); // Optional: Set to null if the related mata_kuliah is deleted

            // Foreign key for tag_bidang_minat, referencing id_bidang_minat in the bidang_minat table
            $table->bigInteger('tag_bidang_minat')->unsigned()->nullable(); // Assuming nullable for optional foreign key
            $table->foreign('tag_bidang_minat')
                ->references('id_bidang_minat')
                ->on('bidang_minat')
                ->onDelete('set null'); // Optional: Set to null if the related bidang_minat is deleted

            // Timestamps for created_at and updated_at
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
