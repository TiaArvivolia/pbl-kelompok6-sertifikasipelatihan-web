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
        Schema::table('pengajuan_pelatihan', function (Blueprint $table) {
            // Add the id_pengguna column as a foreign key referencing the id_pengguna column in pengguna table
            $table->bigInteger('id_pengguna')->unsigned()->after('id_pelatihan');
            $table->foreign('id_pengguna')
                ->references('id_pengguna')
                ->on('pengguna')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('pengajuan_pelatihan', function (Blueprint $table) {
            $table->dropForeign(['id_pengguna']);
            $table->dropColumn('id_pengguna');
        });
    }
};
