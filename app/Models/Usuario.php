<?php


namespace App\Models;


use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;

/**
 * Usuario
 *
 * @property integer $RUT_USUARIO
 * @property integer $ID_ESTADO
 * @property string $NOMBRES_USUARIO
 * @property string $APAT_USUARIO
 * @property string $AMAT_USUARIO
 * @property string $USERNAME
 * @property string $PASSWORD
 * @property string $FECHA_CREACION
 * @property string $FECHA_MODIFICACION
 * @property integer $ID_USUARIO_CREACION
 * @property integer $ID_USUARIO_MODIFICACION
 * @property string $FECHA_CADUCIDAD
 * @property integer $INTENTOS_LOGIN
 * @property string $MAIL
 * @property integer $ID_PERFIL
 * @property integer $ID_INSTITUCION
 * @property integer $REG_ID
 * @property string $REMEMBER_TOKEN
 */
class Usuario extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    protected $table = 'ab_usuario';
    protected $primaryKey = 'rut_usuario';
    public $timestamps = false;


    public function Comunas()
    {
        return $this->hasMany('App\Models\UsuarioComuna', 'rut_usuario', 'rut_usuario');
    }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password');

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberRunCiudadano()
    {
        return $this->remember_run_ciudadano;
    }

    public function setRememberRunCiudadano($value)
    {
        $this->remember_run_ciudadano = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function getFullNameAttribute()
    {
        return $this->nombres_usuario . ' ' . $this->apat_usuario . ' ' . $this->amat_usuario;
    }

    public function getAuthIdentifierName()
    {
        // TODO: Implement getAuthIdentifierName() method.
    }

    public function getEmailForPasswordReset()
    {
        // TODO: Implement getEmailForPASSWORDRESET()thod.
    }

    public function sendPasswordResetNotification($token)
    {
        // TODO: Implement sendPasswordResetNotification() method.
    }

    /**
     * deja en null el campo hash del usuario
     */
    public static function limpiaHash($usuarioRun)
    {

    }
}
