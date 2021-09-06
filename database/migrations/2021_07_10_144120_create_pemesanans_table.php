<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemesanansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->text('alamat')->nullable();
            $table->string('email');
            $table->string('no_hp');
            $table->text('catatan')->nullable();
            $table->string('referal')->nullable();
            $table->unsignedBigInteger('id_transaksi');
            $table->timestamps();

            $table->foreign('id_transaksi')->references('id')->on('transaksis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemesanans');
    }
}
