<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CriaTabelaPipeline extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('pipeline', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cliente')->nullable();
            $table->string('idtotem')->nullable();
            $table->string('qualificacao')->nullable();
            $table->string('proposta')->nullable();
            $table->string('negociacao')->nullable();
            $table->string('fechamento')->nullable();
            $table->string('idautor')->nullable();
            $table->string('id_ult_alterador')->nullable();
            $table->string('excluidopipeline')->nullable();
            $table->string('datainicial')->nullable();
            $table->string('datafinal')->nullable();
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
        Schema::dropIfExists('pipeline');

    }
}
