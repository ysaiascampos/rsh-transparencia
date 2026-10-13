<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargos extends Model
{
    protected $table = 'rsht_tipo_cargos';
    protected $primaryKey = 'id_cargo';
    public $timestamps = false;
}
