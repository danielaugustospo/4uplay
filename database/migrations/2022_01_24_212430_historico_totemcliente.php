<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HistoricoTotemcliente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('historico_totemcliente', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('h_idcliente')->nullable();;
            $table->string('h_idtotem')->nullable();
            $table->string('h_totemassociado')->nullable();
            $table->string('h_dtassociado')->nullable();
            $table->string('h_excluidototem')->nullable();
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
        Schema::dropIfExists('historico_totemcliente');
    }
}
