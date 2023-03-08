<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstansiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instansi', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title', 50);
            $table->string('singkatan', 9);
            $table->string('nama', 255);
            $table->string('logo', 255);
            $table->string('path', 255);
            $table->string('kota', 255);
            $table->text('alamat', 255);
            $table->string('telp', 22);
            $table->string('wa', 22);
            $table->string('email', 50);
            $table->string('website', 100);
            $table->text('informasi');
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
        Schema::dropIfExists('instansi');
    }
}
