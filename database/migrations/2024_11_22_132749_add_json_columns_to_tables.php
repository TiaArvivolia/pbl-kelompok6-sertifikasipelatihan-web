<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJsonColumnsToTables extends Migration
{
    public function up()
    {
        // Add json columns to dosen table
        Schema::table('dosen', function (Blueprint $table) {
            $table->json('mk_list')->nullable();
            $table->json('bidang_minat_list')->nullable();
        });

        // Add json columns to riwayat_pelatihan table
        Schema::table('riwayat_pelatihan', function (Blueprint $table) {
            $table->json('mk_list')->nullable();
            $table->json('bidang_minat_list')->nullable();
        });

        // Add json columns to riwayat_sertifikasi table
        Schema::table('riwayat_sertifikasi', function (Blueprint $table) {
            $table->json('mk_list')->nullable();
            $table->json('bidang_minat_list')->nullable();
        });

        // Add json columns to daftar_pelatihan table
        Schema::table('daftar_pelatihan', function (Blueprint $table) {
            $table->json('mk_list')->nullable();
            $table->json('bidang_minat_list')->nullable();
        });
    }

    public function down()
    {
        // Drop json columns from dosen table
        Schema::table('dosen', function (Blueprint $table) {
            $table->dropColumn('mk_list');
            $table->dropColumn('bidang_minat_list');
        });

        // Drop json columns from riwayat_pelatihan table
        Schema::table('riwayat_pelatihan', function (Blueprint $table) {
            $table->dropColumn('mk_list');
            $table->dropColumn('bidang_minat_list');
        });

        // Drop json columns from riwayat_sertifikasi table
        Schema::table('riwayat_sertifikasi', function (Blueprint $table) {
            $table->dropColumn('mk_list');
            $table->dropColumn('bidang_minat_list');
        });

        // Drop json columns from daftar_pelatihan table
        Schema::table('daftar_pelatihan', function (Blueprint $table) {
            $table->dropColumn('mk_list');
            $table->dropColumn('bidang_minat_list');
        });
    }
}