<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HistoricoMensalidade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('historicomensalidade', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('idmensalidade')->nullable();;
            $table->string('ano')->nullable();;
            $table->string('valor')->nullable();
            $table->string('id_usr_criador')->nullable();
            $table->string('operacao')->nullable();
            $table->string('dataoperacao')->nullable();
            $table->string('excluidomensalidade')->nullable();
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
        Schema::dropIfExists('historicomensalidade');

    }
}
