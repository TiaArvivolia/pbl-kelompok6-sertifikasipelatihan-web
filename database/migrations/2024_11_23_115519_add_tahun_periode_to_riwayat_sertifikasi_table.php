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
        Schema::table('riwayat_sertifikasi', function (Blueprint $table) {
            $table->year('tahun_periode')->nullable(); // Add the tahun_periode column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('riwayat_sertifikasi', function (Blueprint $table) {
            $table->dropColumn('tahun_periode'); // Drop the column if we rollback
        });
    }
};
