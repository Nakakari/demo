<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailMuatEceranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_muat_eceran', function (Blueprint $table) {
            $table->bigIncrements('id_detail_muat_eceran');
            $table->foreignId('muat_eceran_id')->references('id_muat_eceran')->on('muat_eceran');
            $table->foreignId('identitas_id')->references('id_identitas')->on('resi_identitas');
            $table->foreignId('status_sent')->references('id_stst_pngrmn')->on('tbl_status_pengiriman');
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('detail_muat_eceran');
    }
}
