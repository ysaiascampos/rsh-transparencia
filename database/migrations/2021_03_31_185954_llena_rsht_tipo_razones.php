<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TipoRazon;

class LlenaRshtTipoRazones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $registro = new TipoRazon();
        $registro->id_razon = 1;
        $registro->descripcion_razon = 'Correo de jefatura';
        $registro->estado = 1;
        $registro->texto_opcional=0;
        $registro->save();

        $registro = new TipoRazon();
        $registro->id_razon = 2;
        $registro->descripcion_razon = 'Correo/Solicitud de ejecutor';
        $registro->estado = 1;
        $registro->texto_opcional=0;
        $registro->save();

        $registro = new TipoRazon();
        $registro->id_razon = 3;
        $registro->descripcion_razon = 'Oficio';
        $registro->estado = 1;
        $registro->texto_opcional=0;
        $registro->save();

        $registro = new TipoRazon();
        $registro->id_razon = 4;
        $registro->descripcion_razon = 'Memo';
        $registro->estado = 1;
        $registro->texto_opcional=0;
        $registro->save();

        $registro = new TipoRazon();
        $registro->id_razon = 5;
        $registro->descripcion_razon = 'Otro (Especificar cual)';
        $registro->estado = 1;
        $registro->texto_opcional=1;
        $registro->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        TipoRazon::where('id_razon',1)->delete();
        TipoRazon::where('id_razon',2)->delete();
        TipoRazon::where('id_razon',3)->delete();
        TipoRazon::where('id_razon',4)->delete();
        TipoRazon::where('id_razon',5)->delete();
    }
}
