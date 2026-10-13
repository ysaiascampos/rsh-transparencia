<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableRshtUsuariosAuth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('rsht_usuarios_auth', 'password')) {
            Schema::table('rsht_usuarios_auth', function (Blueprint $table) {
                $table->string('password', 250)->after('nombre')->default('default');
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
        if (!Schema::hasColumn('rsht_usuarios_auth', 'password')) {
            Schema::table('rsht_usuarios_auth', function (Blueprint $table) {
                $table->dropColumn('password');
            });
        }
    }
}
