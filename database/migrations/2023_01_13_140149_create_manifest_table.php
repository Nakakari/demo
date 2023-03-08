<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManifestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manifest', function (Blueprint $table) {
            $table->bigIncrements('id_manifest');
            $table->string('no_manifest', 255)->nullable();
            $table->string('driver', 255);
            $table->string('no_tlp_driver', 255)->nullable();
            $table->string('nopol', 255)->nullable();
            $table->string('jenis_kendaraan', 255)->nullable();
            $table->string('tujuan', 255)->nullable();
            $table->date('estimasi_tiba')->nullable();
            $table->date('tgl_buat')->nullable();
            $table->integer('status');
            $table->foreignId('id_cabang')->references('id_cabang')->on('cabang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manifest');
    }
}
