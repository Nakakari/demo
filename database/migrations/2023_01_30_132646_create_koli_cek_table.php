<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKoliCekTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('koli_cek', function (Blueprint $table) {
            $table->bigIncrements('id_kolicek');
            $table->string('driver', 255);
            $table->string('nopol', 255)->nullable();
            $table->date('tgl_buat')->nullable();
            $table->bigInteger('jumlah_resi')->nullable();
            $table->bigInteger('jumlah_koli')->nullable();
            $table->bigInteger('jumlah_resi2')->nullable();
            $table->bigInteger('jumlah_koli2')->nullable();
            $table->foreignId('id_cabang')->references('id_cabang')->on('cabang');
            $table->string('id_cabang2')->nullable();
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
        Schema::dropIfExists('koli_cek');
    }
}
