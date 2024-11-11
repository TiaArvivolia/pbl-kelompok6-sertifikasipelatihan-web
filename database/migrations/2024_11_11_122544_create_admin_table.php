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
        Schema::create('admin', function (Blueprint $table) {
            // Define primary key for the admin table
            $table->id('id_admin');

            // Define the foreign key for id_pengguna as bigInteger
            $table->bigInteger('id_pengguna')->unsigned();

            // Define other columns for the admin table
            $table->string('nama_lengkap', 100);
            $table->string('nip', 20);
            $table->string('no_telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('gambar_profil', 255)->nullable();
            $table->timestamps();

            // Set up the foreign key constraint
            $table->foreign('id_pengguna')
                ->references('id_pengguna')
                ->on('pengguna')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};