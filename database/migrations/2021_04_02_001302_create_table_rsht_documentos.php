<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRshtDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rsht_documentos', function (Blueprint $table) {
            $table->id('id_documento')->unsigned();
            $table->string('nombre_original');
            $table->string('nombre_sistema');
            $table->datetime('fecha_carga');
            $table->integer('estado');
            $table->string('texto_opcionalRazon')->nullable();
            $table->integer('id_razon')->unsigned();
            $table->integer('id_consulta')->unsigned();
            $table->foreign('id_razon')->references('id_razon')->on('rsht_tipo_razones');
            $table->foreign('id_consulta')->references('id_consulta')->on('rsht_log_consultas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rsht_documentos');
    }
}
