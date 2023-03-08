<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailKoliTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_koli', function (Blueprint $table) {
            $table->bigIncrements('id_detail_koli');
            $table->foreignId('identitas_id')->references('id_identitas')->on('resi_identitas');
            $table->foreignId('koli_id')->references('id_kolicek')->on('koli_cek');
            $table->foreignId('cabang_id')->references('id_cabang')->on('cabang');
            $table->foreignId('cabang_id2')->nullable()->references('id_cabang')->on('cabang');
            $table->foreignId('created_by')->references('id')->on('users');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_koli');
    }
}
