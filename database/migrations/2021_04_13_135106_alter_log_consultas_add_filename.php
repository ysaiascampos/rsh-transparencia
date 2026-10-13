<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLogConsultasAddFilename extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('rsht_log_consultas', 'nombre_archivo')) {
            Schema::table('rsht_log_consultas', function (Blueprint $table) {
                $table->string('nombre_archivo', 250);
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
        if (!Schema::hasColumn('rsht_log_consultas', 'nombre_archivo')) {
            Schema::table('rsht_log_consultas', function (Blueprint $table) {
                $table->dropColumn('nombre_archivo');
            });
        }
    }
}
