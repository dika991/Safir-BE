<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToJemaahs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jemaahs', function (Blueprint $table) {
            $table->unsignedBigInteger('id_paket');

            $table->foreign('id_paket')->references('id')->on('tipes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jemaahs', function (Blueprint $table) {
            $table->dropForeign(['id_paket']);
            $table->dropColumn('id_paket');
        });
    }
}
