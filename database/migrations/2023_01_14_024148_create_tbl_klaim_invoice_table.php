<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblKlaimInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_klaim_invoice', function (Blueprint $table) {
            $table->bigIncrements('id_klaim_invoice');
            $table->foreignId('id_invoice')->references('id_invoice')->on('tbl_invoice');
            $table->string('klaim', 255);
            $table->bigInteger('nominal_klaim');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_klaim_invoice');
    }
}
