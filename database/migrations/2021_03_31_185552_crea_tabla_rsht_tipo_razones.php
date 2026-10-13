<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaTablaRshtTipoRazones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rsht_tipo_razones', function (Blueprint $table) {
            $table->id('id_razon')->unsigned();
            $table->string('descripcion_razon');
            $table->integer('estado');
            $table->integer('texto_opcional');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rsht_tipo_razones');
    }
}
