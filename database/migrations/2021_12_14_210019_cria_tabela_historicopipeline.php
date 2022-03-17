<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CriaTabelaHistoricopipeline extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historicopipeline', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('h_idpipeline')->nullable();
            $table->string('h_cliente')->nullable();
            $table->string('h_idtotem')->nullable();
            $table->string('h_qualificacao')->nullable();
            $table->string('h_proposta')->nullable();
            $table->string('h_negociacao')->nullable();
            $table->string('h_fechamento')->nullable();
            $table->string('h_tipooperacao')->nullable();
            $table->string('h_dt_proposta')->nullable();
            $table->string('h_dtoperacao')->nullable();
            $table->string('h_id_ult_alterador')->nullable();
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
        Schema::dropIfExists('historicopipeline');

    }
}
