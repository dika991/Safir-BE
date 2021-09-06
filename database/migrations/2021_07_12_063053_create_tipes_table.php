<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->float('harga');
            $table->integer('kuota');
            $table->unsignedBigInteger('id_paket');
            $table->timestamps();

            $table->foreign('id_paket')->references('id')->on('pakets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipes');
    }
}
