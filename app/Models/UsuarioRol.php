<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioRol extends Model
{
    protected $table = 'rsht_usuarios_rol';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;
}
