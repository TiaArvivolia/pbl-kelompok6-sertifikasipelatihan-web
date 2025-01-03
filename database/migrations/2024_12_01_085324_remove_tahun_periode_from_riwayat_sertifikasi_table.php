<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('riwayat_sertifikasi', function (Blueprint $table) {
            $table->dropColumn('tahun_periode'); // Menghapus kolom tahun_periode
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_sertifikasi', function (Blueprint $table) {
            //
        });
    }
};