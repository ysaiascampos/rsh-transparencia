<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reportes extends Model
{
    protected $table = 'rsht_tipo_reportes';
    protected $primaryKey = 'id_reporte';
    public $timestamps = false;
}
