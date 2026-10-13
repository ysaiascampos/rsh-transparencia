<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaTablaRshtUsuariosPerfilRol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rsht_usuarios_perfil_rol', function (Blueprint $table) {
            $table->id('id');
            $table->integer('id_perfil')->unsigned();
            $table->integer('id_rol')->unsigned();
            $table->integer('estado')->unsigned();
            $table->foreign('id_perfil')->references('id_perfil')->on('rsht_usuarios_perfiles');
            $table->foreign('id_rol')->references('id_rol')->on('rsht_usuarios_rol');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rsht_usuarios_perfil_rol');
    }
}
