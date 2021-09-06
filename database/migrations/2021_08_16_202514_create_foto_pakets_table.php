<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFotoPaketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foto_pakets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_paket');
            $table->string('name');
            $table->string('url');
            $table->string('path');
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
        Schema::dropIfExists('foto_pakets');
    }
}
