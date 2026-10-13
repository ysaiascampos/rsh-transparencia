<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * UsuarioComuna
 *
 * @property integer $RUT_USUARIO
 * @property integer $COM_ID
 * @property integer $ID
 */
class UsuarioComuna extends Model
{
    protected $table = 'ab_usuario_comuna';
    protected $primaryKey = 'com_id';
    public $timestamps = false;
}
