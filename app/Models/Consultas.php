<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultas extends Model
{
    protected $table = 'rsht_log_consultas';
    protected $primaryKey = 'id_consulta';
    public $timestamps = false;
}
