<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResiIdentitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resi_identitas', function (Blueprint $table) {
            $table->bigIncrements('id_identitas');
            $table->foreignId('id_pengiriman')->references('id_pengiriman')->on('pengiriman');
            $table->text('nama_identitas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resi_identitas');
    }
}
