<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DataPopulation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insert some stuff
        DB::table('rsht_usuarios_perfiles')->insert(
            array(
                'id_perfil' => 1,
                'nombre_perfil' => 'Admin',
                'estado' => 1
            )
        );
        // Insert some stuff
        DB::table('rsht_usuarios_perfiles')->insert(
            array(
                'id_perfil' => 2,
                'nombre_perfil' => 'Interno',
                'estado' => 1
            )
        );
        // Insert some stuff
        DB::table('rsht_usuarios_perfiles')->insert(
            array(
                'id_perfil' => 3,
                'nombre_perfil' => 'Externo',
                'estado' => 1
            )
        );
        // Insert some stuff
        DB::table('rsht_usuarios_rol')->insert(
            array(
                'id_rol' => 1,
                'nombre_rol' => 'Carga Masiva',
                'estado' => 1
            )
        );
        // Insert some stuff
        DB::table('rsht_usuarios_rol')->insert(
            array(
                'id_rol' => 2,
                'nombre_rol' => 'Carga Individual',
                'estado' => 1
            )
        );
        // Insert some stuff
        DB::table('rsht_usuarios_rol')->insert(
            array(
                'id_rol' => 3,
                'nombre_rol' => 'Escritorio',
                'estado' => 1
            )
        );
        // Insert some stuff
        DB::table('rsht_usuarios_rol')->insert(
            array(
                'id_rol' => 4,
                'nombre_rol' => 'Externo',
                'estado' => 1
            )
        );
        // Insert some stuff
        DB::table('rsht_usuarios_rol')->insert(
            array(
                'id_rol' => 5,
                'nombre_rol' => 'Interno',
                'estado' => 1
            )
        );
        // Insert some stuff
        DB::table('rsht_usuarios_rol')->insert(
            array(
                'id_rol' => 6,
                'nombre_rol' => 'Mantenedor Usuarios',
                'estado' => 1
            )
        );
        // Insert some stuff
        DB::table('rsht_usuarios_rol')->insert(
            array(
                'id_rol' => 7,
                'nombre_rol' => 'Interno - Ofuscar',
                'estado' => 1
            )
        );

        // Insert some stuff
        DB::table('rsht_usuarios_auth')->insert(
            array(
                'id_usuario' => 1,
                'rut' => 14167698,
                'nombre' => 'Rodrigo Olivos',
                'estado' => 1,
                'perfil' => 1,
                'dependencia'=>1,
                'cargo' =>1,
                'ultimo_acceso' =>new datetime()
            )
        );
        // Insert some stuff
        DB::table('rsht_usuarios_perfil_rol')->insert(
            array(
                'id' => 1,
                'id_perfil' => 1,
                'id_rol' => 1,
                'estado' => 1,
            )

        );
        // Insert some stuff
        DB::table('rsht_usuarios_perfil_rol')->insert(
            array(
                'id' => 2,
                'id_perfil' => 1,
                'id_rol' => 2,
                'estado' => 1,
            )

        );
        // Insert some stuff
        DB::table('rsht_usuarios_perfil_rol')->insert(
            array(
                'id' => 3,
                'id_perfil' => 1,
                'id_rol' => 3,
                'estado' => 1,
            )

        );
        // Insert some stuff
        DB::table('rsht_usuarios_perfil_rol')->insert(
            array(
                'id' => 4,
                'id_perfil' => 1,
                'id_rol' => 4,
                'estado' => 1,
            )

        );
        // Insert some stuff
        DB::table('rsht_usuarios_perfil_rol')->insert(
            array(
                'id' => 5,
                'id_perfil' => 1,
                'id_rol' => 5,
                'estado' => 1,
            )

        );
        // Insert some stuff
        DB::table('rsht_usuarios_perfil_rol')->insert(
            array(
                'id' => 6,
                'id_perfil' => 1,
                'id_rol' => 6,
                'estado' => 1,
            )

        );
        // Insert some stuff
        DB::table('rsht_usuarios_perfil_rol')->insert(
            array(
                'id' => 7,
                'id_perfil' => 2,
                'id_rol' => 7,
                'estado' => 1,
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

    }
}
