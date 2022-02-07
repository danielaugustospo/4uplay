<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Totem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('totem', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('idcliente')->nullable();;
            $table->string('idlicenciado')->nullable();
            $table->string('n_serie')->nullable();
            $table->string('dtassociado')->nullable();
            $table->string('id_usr_criador')->nullable();
            $table->string('id_ult_alterador')->nullable();
            $table->string('excluidototem')->nullable();
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
        Schema::dropIfExists('totem');

    }
}
