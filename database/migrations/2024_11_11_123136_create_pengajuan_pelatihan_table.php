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
        Schema::create('pengajuan_pelatihan', function (Blueprint $table) {
            $table->id('id_pengajuan');
            // Define foreign key for id_pelatihan referencing id_pelatihan in daftar_pelatihan
            $table->bigInteger('id_pelatihan')->unsigned(); 
            $table->foreign('id_pelatihan')
                ->references('id_pelatihan') // Column in the referenced table (daftar_pelatihan)
                ->on('daftar_pelatihan') // Referenced table (daftar_pelatihan)
                ->onDelete('cascade'); // Optional: Delete pengajuan_pelatihan if the referenced daftar_pelatihan is deleted

            $table->date('tanggal_pengajuan');
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_pelatihan');
    }
};