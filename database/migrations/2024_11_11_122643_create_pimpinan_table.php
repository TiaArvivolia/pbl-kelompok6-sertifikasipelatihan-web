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
        Schema::create('pimpinan', function (Blueprint $table) {
            $table->id('id_pimpinan');

            // Define the foreign key relationship for 'id_pengguna'
            $table->bigInteger('id_pengguna')->unsigned();
            $table->foreign('id_pengguna') // Column 'id_pengguna' in this table
                ->references('id_pengguna') // References 'id_pengguna' column in 'pengguna' table
                ->on('pengguna') // Table that holds the referenced column
                ->onDelete('cascade'); // Optional: If the related 'pengguna' is deleted, cascade the delete

            // Other columns in the 'pimpinan' table
            $table->string('nama_lengkap', 100);
            $table->string('nip', 20);
            $table->string('nidn', 20)->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('gambar_profil', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pimpinan');
    }
};