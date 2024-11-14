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
        Schema::create('daftar_pelatihan', function (Blueprint $table) {
            $table->id('id_pelatihan');
            $table->enum('level_pelatihan', ['Nasional', 'Internasional']);
            $table->string('nama_pelatihan', 100);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->integer('kuota')->nullable();
            $table->string('lokasi', 100)->nullable();
            $table->decimal('biaya', 10, 2)->nullable();
            $table->integer('jml_jam')->nullable();

            // Foreign key with 'id_vendor_pelatihan' referencing 'id_vendor_pelatihan' in the 'vendor_pelatihan' table
            $table->bigInteger('id_vendor_pelatihan')->unsigned();
            $table->foreign('id_vendor_pelatihan')->references('id_vendor_pelatihan')->on('vendor_pelatihan')->onDelete('cascade');

            // Foreign key with 'tag_mk' referencing 'id_mata_kuliah' in the 'mata_kuliah' table
            $table->bigInteger('tag_mk')->unsigned()->nullable();  // Make it nullable to allow SET NULL on delete
            $table->foreign('tag_mk')->references('id_mata_kuliah')->on('mata_kuliah')->onDelete('set null');

            // Foreign key with 'tag_bidang_minat' referencing 'id_bidang_minat' in the 'bidang_minat' table
            $table->bigInteger('tag_bidang_minat')->unsigned()->nullable(); // Make it nullable to allow SET NULL on delete
            $table->foreign('tag_bidang_minat')->references('id_bidang_minat')->on('bidang_minat')->onDelete('set null');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_pelatihan');
    }
};