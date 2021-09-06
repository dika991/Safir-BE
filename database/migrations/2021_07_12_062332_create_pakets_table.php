<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pakets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode');
            $table->string('nama');
            $table->string('musim');
            $table->integer('jml_hari');
            $table->date('tgl_mulai');
            $table->date('tgl_berakhir');
            $table->unsignedBigInteger('id_hotel');
            $table->unsignedBigInteger('id_maskapai');
            $table->timestamps();

            $table->foreign('id_hotel')->references('id')->on('hotels');
            $table->foreign('id_maskapai')->references('id')->on('maskapais');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pakets');
    }
}
