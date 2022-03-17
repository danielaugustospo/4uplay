<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Historicocriativo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('historicocriativo', function (Blueprint $table) {
            $table->bigIncrements('hc_id');
            $table->string('hc_idcriativo')->nullable();
            $table->string('hc_idtotem')->nullable();
            $table->string('hc_cliente')->nullable();
            $table->string('hc_tipocriativo')->nullable();
            $table->string('hc_quantidade')->nullable();
            $table->string('hc_valunit')->nullable();
            $table->string('hc_valtotal')->nullable();
            $table->string('hc_tipooperacao')->nullable();
            $table->string('hc_idlicenciado')->nullable();
            $table->string('hc_id_ult_alterador')->nullable();
            $table->string('hc_datacriacao')->nullable();
            $table->string('hc_dataalteracao')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historicocriativo');

    }
}
