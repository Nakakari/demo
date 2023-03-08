<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblDetailInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_detail_invoice', function (Blueprint $table) {
            $table->bigIncrements('id_detail_invoice');
            $table->foreignId('id_invoice')->references('id_invoice')->on('tbl_invoice');
            $table->foreignId('id_pengiriman')->references('id_pengiriman')->on('pengiriman');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_detail_invoice');
    }
}
