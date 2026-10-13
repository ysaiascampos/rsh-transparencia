<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRshtTipoReportes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rsht_tipo_reportes', function (Blueprint $table) {
            $table->id('id_reporte')->unsigned();
            $table->string('nombre_reporte');
            $table->integer('estado');
            $table->string('archivo');
            $table->string('descripcion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rsht_tipo_reportes');
    }
}
