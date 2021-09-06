<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKelengkapansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelengkapans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status');
            $table->unsignedBigInteger('id_inventaris');
            $table->unsignedBigInteger('id_jemaah');
            $table->timestamps();

            $table->foreign('id_inventaris')->references('id')->on('inventaris');
            $table->foreign('id_jemaah')->references('id')->on('jemaahs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kelengkapans');
    }
}
