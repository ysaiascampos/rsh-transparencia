<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dependencia extends Model
{
    protected $table = 'rsht_tipo_dependencias';
    protected $primaryKey = 'id_dependencia';
    public $timestamps = false;
}
