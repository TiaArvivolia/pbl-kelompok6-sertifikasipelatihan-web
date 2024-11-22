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
        Schema::table('riwayat_pelatihan', function (Blueprint $table) {
            // Menambahkan foreign key ke kolom penyelenggara
            $table->foreign('penyelenggara')
                ->references('id_vendor_pelatihan')
                ->on('vendor_pelatihan')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('riwayat_pelatihan', function (Blueprint $table) {
            // Menghapus foreign key jika diperlukan rollback
            $table->dropForeign(['penyelenggara']);
        });
    }
};