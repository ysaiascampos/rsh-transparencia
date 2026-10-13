<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRshtLogConsultas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rsht_log_consultas', function (Blueprint $table) {
            $table->id('id_consulta');
            $table->integer('run_solicitante');
            $table->integer('run_titular');
            $table->integer('razon_justificacion');
            $table->string('texto_justificacion')->nullable();;
            $table->integer('reporte');
            $table->string('cargo');
            $table->integer('perfil')->unsigned();
            $table->datetime('acceso');
            $table->foreign('razon_justificacion')->references('id_razon')->on('RSHT_TIPO_RAZONES');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rsht_log_consultas');
    }
}
