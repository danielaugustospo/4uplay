<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdicionaColunasUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table) {
            $table->string("rzsocial")->nullable();
            $table->string("cnpj")->nullable();
            $table->string("endereco")->nullable();
            $table->string("telefone")->nullable();
            $table->string("wpp")->nullable();
            $table->string("estado")->nullable();
            $table->string("municipio")->nullable();
            $table->string("resmunicipio")->nullable();
            $table->string("desativado")->nullable();
        });    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
