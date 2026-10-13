<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaTablaRshtUsuariosAuth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rsht_usuarios_auth', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->integer('rut');
            $table->string('nombre');
            $table->integer('dependencia');
            $table->integer('cargo');
            $table->datetime('ultimo_acceso');
            $table->integer('estado');
            $table->integer('perfil')->unsigned();
            $table->timestamps();

            $table->foreign('perfil')->references('id_perfil')->on('rsht_usuarios_perfiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rsht_usuarios_auth');
    }
}
