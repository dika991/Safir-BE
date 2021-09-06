<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('tanggal');
            $table->float('nominal');
            $table->text('catatan');
            $table->unsignedBigInteger('id_pemesanan');
            $table->unsignedBigInteger('id_transaksi');
            $table->timestamps();

            $table->foreign('id_pemesanan')->references('id')->on('pemesanans');
            $table->foreign('id_transaksi')->references('id')->on('transaksis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayarans');
    }
}
