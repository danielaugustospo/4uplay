<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Mensalidade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('mensalidade', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ano')->nullable();;
            $table->string('valor')->nullable();
            $table->string('id_usr_criador')->nullable();
            $table->string('id_ult_alterador')->nullable();
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
        Schema::dropIfExists('mensalidade');

    }
}
