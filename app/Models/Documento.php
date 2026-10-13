<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    //
    protected $table = 'rsht_documentos';
    protected $primaryKey = 'id_documento';
    public $timestamps = false;
}
