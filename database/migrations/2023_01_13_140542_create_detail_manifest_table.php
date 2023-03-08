<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailManifestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_manifest', function (Blueprint $table) {
            $table->bigIncrements('id_detail_manifest');
            $table->foreignId('pengiriman_id')->references('id_pengiriman')->on('pengiriman');
            $table->foreignId('manifest_id')->references('id_manifest')->on('manifest');
            $table->date('estimasi');
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_manifest');
    }
}
