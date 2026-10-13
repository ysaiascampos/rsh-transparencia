<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DataPopulation2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insert some stuff
//        DB::table('rsht_log_consultas')->insert(
//            array(
//                'id_consulta' => 1,
//                'run' => 14167698,
//                'nombre' => 6,
//                'reporte' => 1,
//                'cargo' => 1,
//                'perfil' => 1,
//                'acceso' => new datetime()
//            )

//        );
        // Insert some stuff
//        DB::table('rsht_log_consultas')->insert(
//            array(
//                'id_consulta' => 2,
//                'run' => 14167698,
//                'nombre' => 6,
//                'reporte' => 1,
//                'cargo' => 1,
//                'perfil' => 1,
//                'acceso' => new datetime()
//            )
//
//        );
        // Insert some stuff
//        DB::table('rsht_log_consultas')->insert(
//            array(
//                'id_consulta' => 3,
//                'run' => 14167698,
//                'nombre' => 6,
//                'reporte' => 1,
//                'cargo' => 1,
//                'perfil' => 1,
//                'acceso' => new datetime()
//            )
//        );

        // Insert some stuff
        DB::table('rsht_tipo_cargos')->insert(
            array(
                'id_cargo' => 1,
                'nombre_cargo' => 'administrador',
                'estado' => 1
            )


        );
        // Insert some stuff
        DB::table('rsht_tipo_cargos')->insert(
            array(
                'id_cargo' => 2,
                'nombre_cargo' => 'supervisor',
                'estado' => 1
            )


        );
        // Insert some stuff
        DB::table('rsht_tipo_reportes')->insert(
            array(
                'id_reporte' => 1,
                'nombre_reporte' => 'reporte pruebas',
                'estado' => 1,
                'archivo' => '',
                'descripcion' => ''
            )

        );
        // Insert some stuff
        DB::table('rsht_tipo_reportes')->insert(
            array(
                'id_reporte' => 2,
                'nombre_reporte' => 'reporte pruebas2',
                'estado' => 1,
                'archivo' => '',
                'descripcion' => ''
            )

        );

        // Insert some stuff
        DB::table('rsht_tipo_dependencias')->insert(
            array(
                'id_dependencia' => 1,
                'nombre_dependencia' => 'MDS',
                'estado' => 1
            )

        );

        // Insert some stuff
        DB::table('rsht_tipo_dependencias')->insert(
            array(
                'id_dependencia' => 2,
                'nombre_dependencia' => 'SSO',
                'estado' => 1
            )

        );
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
