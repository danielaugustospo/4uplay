<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Clienteslicenciado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('clienteslicenciado', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('c_idlicenciado')->nullable();
            $table->string('c_nome')->nullable();

            $table->string('c_email')->nullable();
            $table->string('c_endereco')->nullable();
            $table->string('c_telefone')->nullable();
            $table->string('c_estado')->nullable();
            $table->string('c_municipio')->nullable();
            
            // $table->string('c_')->nullable();
            // $table->string('h_dtassociado')->nullable();
            $table->string('c_excluido')->nullable();
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
        Schema::dropIfExists('clienteslicenciado');
    }
}
