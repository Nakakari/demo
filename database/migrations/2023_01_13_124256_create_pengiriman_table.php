<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengirimanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->bigIncrements('id_pengiriman');
            $table->foreignId('dari_cabang')->references('id_cabang')->on('cabang');
            $table->string('no_resi', 255);
            $table->string('no_resi_manual', 255)->nullable();
            $table->string('nama_pengirim', 255);
            $table->string('kota_pengirim', 255);
            $table->text('alamat_pengirim');
            $table->string('tlp_pengirim', 255);
            $table->foreignId('id_cabang_tujuan')->references('id_cabang')->on('cabang');
            $table->foreignId('status_sent')->references('id_stst_pngrmn')->on('tbl_status_pengiriman');
            $table->foreignId('id_pelanggan')->nullable()->references('id_pelanggan')->on('pelanggan');
            $table->string('nama_penerima', 255);
            $table->string('kota_penerima', 255);
            $table->text('alamat_penerima');
            $table->string('no_penerima', 255);
            $table->text('isi_barang');
            $table->integer('berat')->nullable();
            $table->integer('volume')->nullable();
            $table->integer('koli');
            $table->string('no_ref')->nullable();
            $table->foreignId('no_pelayanan')->references('id_pelayanan')->on('tipe_pelayanan');
            $table->string('no_moda', 255);
            $table->bigInteger('bea');
            $table->bigInteger('bea_penerus');
            $table->bigInteger('bea_packing');
            $table->bigInteger('asuransi');
            $table->bigInteger('biaya');
            $table->foreignId('tipe_pembayaran')->references('id_pembayaran')->on('tipe_pembayaran');
            $table->date('tgl_masuk');
            $table->text('keterangan')->nullable();
            $table->foreignId('id_transit')->nullable()->references('id_cabang')->on('cabang');
            $table->date('tgl_tiba')->nullable();
            $table->string('file_bukti')->nullable();
            $table->text('ket')->nullable();
            $table->integer('is_buat')->nullable();
            $table->integer('is_kondisi_resi')->nullable();
            $table->integer('is_lunas')->nullable();
            $table->foreignId('id_member_sales')->references('id_member_sales')->on('member_sales');
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
        Schema::dropIfExists('pengiriman');
    }
}
