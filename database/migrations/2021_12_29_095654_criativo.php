<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Criativo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('criativo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cliente')->nullable();;
            $table->string('tipocriativo')->nullable();
            $table->string('quantidade')->nullable();
            $table->string('valunit')->nullable();
            $table->string('valtotal')->nullable();
            $table->string('idlicenciado')->nullable();
            $table->string('id_ult_alterador')->nullable();
            $table->string('excluidocriativo')->nullable();
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
        Schema::dropIfExists('criativo');

    }
}
