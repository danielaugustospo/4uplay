<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CriaTbReajuste extends Migration
{
        /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('tb_reajuste', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('idusuario')->nullable();
            $table->string('valor')->nullable();
            $table->string('dtreajuste')->nullable();;
            $table->string('id_usr_criador')->nullable();
            $table->string('id_ult_alterador')->nullable();
            $table->string('excluidoreajuste')->nullable();
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
        Schema::dropIfExists('tb_reajuste');

    }
}
