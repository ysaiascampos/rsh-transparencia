<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioPerfil extends Model
{
    protected $table = 'rsht_usuarios_perfiles';
    protected $primaryKey = 'id_perfil';
    public $timestamps = false;
}
