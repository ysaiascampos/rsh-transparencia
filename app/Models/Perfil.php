<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Perfil
 *
 * @property integer $ID_PERFIL
 * @property integer $ID_NIVEL
 * @property string $NOMBRE_PERFIL
 * @property integer $ORDEN_DESPLIEGUE_PERFIL
 * @property integer $ESTADO
 * @property integer $PERFIL_GUIA
 */
class Perfil extends Model
{
    protected $table = 'ab_perfil';
    protected $primaryKey = 'id_perfil';
    public $timestamps = false;

    /* Lista perfiles válidos
     * queda fuera el estado = 2, que es un perfil sólo interno
     */
    public static function listarPerfilesValidos()
    {

    }

}
