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
        Schema::create('pengajuan_pelatihan_peserta', function (Blueprint $table) {
            $table->id('id_pengajuan_peserta');

            // Foreign key for id_pengajuan referencing id_pelatihan in pengajuan_pelatihan table
            $table->bigInteger('id_pengajuan')->unsigned(); 
            $table->foreign('id_pengajuan')
                ->references('id_pengajuan')
                ->on('pengajuan_pelatihan')
                ->onDelete('cascade'); // Optional: Delete related rows when the parent row is deleted

            // Foreign key for id_pengguna referencing id_pengguna in pengguna table
            $table->bigInteger('id_pengguna')->unsigned(); 
            $table->foreign('id_pengguna')
                ->references('id_pengguna')
                ->on('pengguna')
                ->onDelete('cascade'); // Optional: Delete related rows when the parent row is deleted

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_pelatihan_peserta');
    }
};