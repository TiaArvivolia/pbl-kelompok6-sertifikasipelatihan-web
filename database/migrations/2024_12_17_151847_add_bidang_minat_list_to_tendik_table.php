<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBidangMinatListToTendikTable extends Migration
{
    public function up()
    {
        Schema::table('tendik', function (Blueprint $table) {
            $table->json('bidang_minat_list')->nullable(); // Ganti 'last_column' dengan kolom terakhir tabel tendik.
        });
    }

    public function down()
    {
        Schema::table('tendik', function (Blueprint $table) {
            $table->dropColumn('bidang_minat_list');
        });
    }
}