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
        Schema::create('pengguna', function (Blueprint $table) {
            // Create the pengguna table
            $table->id('id_pengguna');
            $table->string('username', 50);
            $table->string('password', 100);
            $table->timestamps();

            // Add id_jenis_pengguna column as bigInteger
            $table->bigInteger('id_jenis_pengguna')->unsigned();

            // Define the foreign key relationship
            $table->foreign('id_jenis_pengguna')
                ->references('id_jenis_pengguna')
                ->on('jenis_pengguna')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};
