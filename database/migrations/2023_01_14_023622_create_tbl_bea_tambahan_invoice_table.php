<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblBeaTambahanInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_bea_tambahan_invoice', function (Blueprint $table) {
            $table->bigIncrements('id_bea_tambahan_invoice');
            $table->foreignId('id_invoice')->references('id_invoice')->on('tbl_invoice');
            $table->string('bea_tambahan', 255);
            $table->bigInteger('nominal_bea');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_bea_tambahan_invoice');
    }
}
