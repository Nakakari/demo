<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking', function (Blueprint $table) {
            $table->bigIncrements('id_tracking');
            $table->uuid('uuid')->unique();
            $table->foreignId('pengiriman_id')->references('id_pengiriman')->on('pengiriman');
            $table->foreignId('status_sent')->references('id_stst_pngrmn')->on('tbl_status_pengiriman');
            $table->foreignId('cabang_id')->nullable()->references('id_cabang')->on('cabang');
            $table->string('file_bukti')->nullable();
            $table->string('ket')->nullable();
            $table->foreignId('created_by')->references('id')->on('users');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users');
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
        Schema::dropIfExists('tracking');
    }
}
