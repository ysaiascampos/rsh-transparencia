<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoRazon extends Model
{
    protected $table = 'rsht_tipo_razones';
    protected $primaryKey = 'id_razon';
    public $timestamps = false;
}
