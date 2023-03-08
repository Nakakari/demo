<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_invoice', function (Blueprint $table) {
            $table->bigIncrements('id_invoice');
            $table->string('no_invoice', 255);
            $table->foreignId('id_bank')->references('id_bank')->on('bank');
            $table->date('jatuh_tempo');
            $table->string('pembuat', 255);
            $table->string('mengetahui', 255)->nullable();
            $table->date('diterbitkan');
            $table->float('ppn');
            $table->text('keterangan');
            $table->timestamps();
            $table->bigInteger('biaya_invoice');
            $table->foreignId('id_pelanggan')->references('id_pelanggan')->on('pelanggan');
            $table->tinyInteger('is_lunas');
            $table->bigInteger('ttl_biaya_invoice');
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_invoice');
    }
}
