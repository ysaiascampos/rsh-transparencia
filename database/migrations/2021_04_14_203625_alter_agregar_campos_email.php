<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAgregarCamposEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('rsht_log_consultas', 'email')) {
            Schema::table('rsht_log_consultas', function (Blueprint $table) {
                $table->string('email', 100)->nullable();
            });
        }

        if (!Schema::hasColumn('rsht_log_consultas', 'correo_enviado')) {
            Schema::table('rsht_log_consultas', function (Blueprint $table) {
                $table->tinyInteger('correo_enviado')->default(0);
            });
        }

        if (!Schema::hasColumn('rsht_usuarios_auth', 'email')) {
            Schema::table('rsht_usuarios_auth', function (Blueprint $table) {
                $table->string('email', 100)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('rsht_log_consultas', 'email')) {
            Schema::table('rsht_log_consultas', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }

        if (!Schema::hasColumn('rsht_log_consultas', 'correo_enviado')) {
            Schema::table('rsht_log_consultas', function (Blueprint $table) {
                $table->dropColumn('correo_enviado');
            });
        }

        if (!Schema::hasColumn('rsht_usuarios_auth', 'email')) {
            Schema::table('rsht_usuarios_auth', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }
    }
}
