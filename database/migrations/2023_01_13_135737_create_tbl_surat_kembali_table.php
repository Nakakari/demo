<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblSuratKembaliTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_surat_kembali', function (Blueprint $table) {
            $table->bigIncrements('id_surat_kembali');
            $table->foreignId('id_pengiriman')->references('id_pengiriman')->on('pengiriman');
            $table->string('no_resi', 255);
            $table->date('tgl_surat_kembali')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('is_isi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_surat_kembali');
    }
}
