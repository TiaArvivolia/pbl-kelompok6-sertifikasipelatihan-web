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
        Schema::table('riwayat_pelatihan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_periode')->nullable(); // Menambahkan kolom id_periode
            $table->foreign('id_periode')->references('id_periode')->on('periode')->onDelete('cascade'); // Relasi dengan tabel periode
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_pelatihan', function (Blueprint $table) {
            $table->dropForeign(['id_periode']); // Menghapus relasi foreign key
            $table->dropColumn('id_periode'); // Menghapus kolom id_periode
        });
    }
};