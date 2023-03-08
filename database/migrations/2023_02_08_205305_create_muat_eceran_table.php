<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMuatEceranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('muat_eceran', function (Blueprint $table) {
            $table->bigIncrements('id_muat_eceran');
            $table->string('driver', 30);
            $table->string('nopol', 30);
            $table->string('no_tlp', 30);
            $table->string('jenis_kendaraan', 30);
            $table->integer('jumlah_resi');
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('muat_eceran');
    }
}
