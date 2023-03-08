<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblPelunasanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pelunasan', function (Blueprint $table) {
            $table->bigIncrements('id_pelunasan');
            $table->foreignId('id_invoice')->references('id_invoice')->on('tbl_invoice');
            $table->bigInteger('nominal_pelunasan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_pelunasan');
    }
}
