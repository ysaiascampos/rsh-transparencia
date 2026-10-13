<?php

namespace App\Classes;

use App\Constantes\Constante;
use App\Http\Controllers\Utiles;
use App\Models\Usuario;
use Exception;
use Illuminate\Support\Facades\Log;
use SoapClient;

class SSOSoapCliente
{
    const RESULTADO_OK = 1;
    const RESULTADO_NO_OK = 0;
    const RESULTADO_EXCEPTION = -2;
    const RESULTADO_FALLO_ASIGNAR_ROLES = -4;
    const RESULTADO_FALLO_CAMBIAR_PASSWORD = -8;
    const USUARIO_NO_ENCONTRADO = "no encontrado";
    const USUARIO_ENCONTRADO = "encontrado";
    const ERROR_RESULTADO_DOBLE = 2;
    const NO_TIENE_CORREO = -11;
    const CORREO_NO_VALIDO = -13;
    const STR_SIN_CORREO = "NO TIENE CORREO";
    const STR_NO_VALIDO_CORREO = "NO TIENE CORREO";
    #const migracionAID  = "ETkOTAEcLhupzaSJ2Fvx0A==";
    #const migracionRolAID = "101";
    #const migracionAppAID = "63";

    /**
     * @param $run
     * @param $dv
     * @return int Cualquier valor distinto a 1 es un error
     */
    public static function getSSO_AsignarRoles($run, $dv)
    {
        $rutConcatenado = $run . "-" . $dv;
        $rol = env('SSO_ROL_DEFECTO');
        $aid = env('AID', "");
        try {
            $ws_sso = new SoapClient(env('SSO_WS_URL'), array('cache_wsdl' => WSDL_CACHE_NONE));
            if ($ws_sso) {
                $ws_sso->__setLocation(env('SSO_WS_URL'));
                $rolArr = array('int' => $rol);
                $consultaSoap = $ws_sso->AsignarRoles(array('rut' => $rutConcatenado, 'roles' => $rolArr, 'AID' => $aid)); // se envia segun estructura recepcion SOAP ( 1 subarreglos)
                $consultaSoapResult = $consultaSoap->AsignarRolesResult;
                if ($consultaSoapResult->Estado == '1') {
                    $var = "Resultado exitoso";
                    $valor = self::RESULTADO_OK;
                } else {
                    $var = "Resultado NO exitoso";
                    $valor = self::RESULTADO_NO_OK;
                }
            }
        } catch (Exception $e) {
            //$q = DB::getQueryLog();
            Log::error("getSSO_AsignarRoles -- SSOSoapCliente.php " . $e->getMessage());
            return self::RESULTADO_EXCEPTION;
        }
        return ($valor);
    }

    public static function getSSO_Autorizar($token)
    {
        try {
            $ws_sso = new SoapClient(env('SSO_WS_URL'), array('cache_wsdl' => WSDL_CACHE_NONE));
            if ($ws_sso) {
                $ws_sso->__setLocation((env('SSO_WS_URL')));
                $consulta_soap = $ws_sso->Autorizar(array('token' => $token));// se envia segun estructura recepcion SOAP
                $consulta_soap = $consulta_soap->AutorizarResult;
                if ($consulta_soap->Estado == '1') {
                    $valor = array('Estado' => self::RESULTADO_OK, 'rut' => $consulta_soap->Usuario->RUT);
                } else
                    $valor = array('Estado' => self::RESULTADO_NO_OK, 'rut' => '0');
            }
        } catch (Exception $e) {
            //$q = DB::getQueryLog();
            Log::error("getSSO_Autorizar -- SSOSoapCliente.php " . $e->getMessage());
            return self::RESULTADO_EXCEPTION;
        }
        return ($valor);
    }

    public static function getSSO_BuscarUsuario($run, $dv)
    {
        $rut_concatenado = $run . "-" . $dv;
        $aid = env('AID', "");
        try {
            $ws_sso = new SoapClient(env('SSO_WS_URL'), array('cache_wsdl' => WSDL_CACHE_NONE));
            if ($ws_sso) {
                $ws_sso->__setLocation(env('SSO_WS_URL'));
                $consulta_soap = $ws_sso->BuscarUsuario(array('rut' => $rut_concatenado, 'AID' => $aid));
                if ($consulta_soap) {
                    $consulta_soap = $consulta_soap->BuscarUsuarioResult;
                    $rCantidad = (int)$consulta_soap->Cantidad;
                    if ($rCantidad >= 1) {
                        if ($rCantidad == 1) {
                            $resultado_ws = $consulta_soap->Resultado;
                            $resultado_ws = $resultado_ws->Usuario;
                            $nombre = $resultado_ws->Nombre;
                            $correo = $resultado_ws->Correo;
                            $habilitado = $resultado_ws->Habilitado;
                            $path = $resultado_ws->Path;
                            $tipo = $resultado_ws->Tipo;
                            $resultado = array(
                                "estado" => self::USUARIO_ENCONTRADO,
                                "nombres" => $nombre,
                                "correo" => $correo,
                                "habilitado" => $habilitado,
                                "path" => $path,
                                "tipo" => $tipo
                            );
                        }else {
                            $resultado_ws = $consulta_soap->Resultado;
                            $resultado_ws = $resultado_ws->Usuario[0];
                            $nombre = $resultado_ws->Nombre;
                            $correo = $resultado_ws->Correo;
                            $habilitado = $resultado_ws->Habilitado;
                            $path = $resultado_ws->Path;
                            $tipo = $resultado_ws->Tipo;
                            $resultado = array(
                                "estado" => self::USUARIO_ENCONTRADO,
                                "nombres" => $nombre,
                                "correo" => $correo,
                                "habilitado" => $habilitado,
                                "path" => $path,
                                "tipo" => $tipo
                            );
                        }
                    } else {
                        $resultado = array("estado" => self::USUARIO_NO_ENCONTRADO);
                    }
                }
            }
        } catch (Exception $e) {
            //$q = DB::getQueryLog();
            Log::error("SSOSoapCliente.php -- getSSO_BuscarUsuario " . $e->getMessage());
            return self::RESULTADO_EXCEPTION;
        }
        return ($resultado);
    }

    public static function getSSO_BuscarUsuarioCombinado($run, $checkUsuario = false, $pass_ws_sso = null)
    {
        $dv = Utiles::dv($run);
        $oUsuario = Usuario::where('per_run', $run)->first();
        $usuarioSSO = self::getSSO_BuscarUsuario($run, $dv);
        if ($checkUsuario && ($oUsuario != null) && ($usuarioSSO ['estado'] == self::USUARIO_NO_ENCONTRADO)) {
            $usuarioSSO['habilitado'] = $oUsuario->id_estado == 1 ? true : false;
            if ($pass_ws_sso) {
                $generaPassword = $pass_ws_sso;
            } else {
                $generaPassword = Utiles::generaContrasena($run);
            }
            $nombres_ = Utiles::cambiaTildeASuLetra($oUsuario->nombres_usuario);
            $apellido_pat = Utiles::cambiaTildeASuLetra($oUsuario->apat_usuario);
            $apellido_mat = Utiles::cambiaTildeASuLetra($oUsuario->amat_usuario);
            $nombre = ucfirst(strtolower($nombres_)) . " " . ucfirst(strtolower($apellido_pat)) . " " . ucfirst(strtolower($apellido_mat));
            $tipo = "EXTERNO";
            $crear = SSOSoapCliente::getSSO_CrearUsuario($run, $dv, $generaPassword, $oUsuario->mail, $usuarioSSO['habilitado'], $nombre, $tipo);
            if ($oUsuario->id_estado == Constante::ESTADO_1_ACTIVO) {
                $habilita_deshab_ws_sso = SSOSoapCliente::getSSO_AsignarRoles($run, $dv);
            } else {
                $habilita_deshab_ws_sso = SSOSoapCliente::getSSO_DeshabilitarUsuario($run, $dv);
                $habilita_deshab_ws_sso = SSOSoapCliente::getSSO_EliminarUsuario($run, $dv);
            }
            $oUsuario ['nuevoUsuario'] = ($crear ? 1 : 0);
        }
        if (!$checkUsuario && ($usuarioSSO ['estado'] == self::USUARIO_NO_ENCONTRADO)) {
            $usuarioSSO['habilitado'] = false;
        }
        $oUsuario->id_estado = ($usuarioSSO['habilitado'] ? 1 : 0);
        return $oUsuario;
    }

    /**
     * @param $run
     * @param $dv
     * @param $passNueva
     * @return int Cualquier valor distinto a 1 es error
     */
    public static function getSSO_CambiarClave($run, $dv, $passNueva)
    {
        $rut_concatenado = $run . "-" . $dv;
        $aid = env('AID', "");
        $asignarRoles = self::getSSO_AsignarRoles($run, $dv);
        try {
            $ws_sso = new SoapClient(env('SSO_WS_URL'), array('cache_wsdl' => WSDL_CACHE_NONE));
            if ($ws_sso) {
                $ws_sso->__setLocation($_ENV['sso_ws_url']);
                $consulta_soap = $ws_sso->CambiarClave(array('rut' => $rut_concatenado, 'claveActual' => '', 'claveNueva' => $passNueva, 'AID' => $aid)); // se envia segun estructura recepcion SOAP
                $consulta_soap = $consulta_soap->CambiarClaveResult;
                if ($consulta_soap->Estado == '1') {
                    $asignarRoles = self::getSSO_AsignarRoles($run, $dv);
                    if ($asignarRoles == "1") {
                        $resultado = array('valor' => self::RESULTADO_OK, 'mensaje' => "Resultado exitoso");
                    } else {
                        $resultado = array('valor' => self::RESULTADO_FALLO_ASIGNAR_ROLES, 'mensaje' => "Resultado exitoso pero fallo en asignar roles");
                    }
                } else {
                    $resultado = array('valor' => self::RESULTADO_FALLO_CAMBIAR_PASSWORD, 'mensaje' => $consulta_soap->Detalle);
                }
            }
        } catch (Exception $e) {
            Log::error("getSSO_CambiarClave -- SSOSoapCliente.php " . $e->getMessage());
            return [
                'valor' => self::RESULTADO_EXCEPTION,
                'mensaje' => $consulta_soap->Detalle
            ];
        }
        return ($resultado);
    }

    public static function getSSO_CrearUsuario($run, $dv, $pass, $correo, $habilitado, $nombre, $tipo)
    {
        $rut_concatenado = $run . "-" . $dv;
        $aid = env('AID', "");
        $aid_rol = $_ENV['sso_rol_defecto'];
        $aid_id_app = $_ENV ['sso_id_app'];
        $aid_nombre = $_ENV ['sso_nombre'];

        try {
            $ws_sso = new SoapClient(env('SSO_WS_URL'), array('cache_wsdl' => WSDL_CACHE_NONE));
            if ($ws_sso) {
                $ws_sso->__setLocation($_ENV['sso_ws_url']);
                $arreglo3 = array('ID' => $aid_rol, 'Id_Aplicacion' => $aid_id_app, 'Nombre' => $aid_nombre);
                $arreglo2 = array('Rol' => $arreglo3);
                $arreglo1 = array('Clave' => $pass, 'Correo' => $correo, 'Habilitado' => $habilitado, 'Nombre' => $nombre, 'Roles' => $arreglo2, 'RUT' => $rut_concatenado, 'Tipo' => $tipo);
                $consulta_soap = $ws_sso->CrearUsuario(array('Usuario' => $arreglo1, 'AID' => $aid)); // se envia segun estructura recepcion SOAP ( 3 subarreglos)
                $consulta_soap = $consulta_soap->CrearUsuarioResult;
                if ($consulta_soap->Estado == "1") {
                    // "Creado con exito";
                    $valor = self::RESULTADO_OK;
                } else {
                    // "No Creado";
                    $valor = self::RESULTADO_NO_OK;
                }
            }
        } catch (Exception $e) {
            //$q = DB::getQueryLog();
            Log::error("getSSO_Login -- SSOSoapCliente.php " . $e->getMessage());
            return self::RESULTADO_EXCEPTION;
        }
        return ($valor);
    }

    public static function getSSO_ModificarUsuario($run, $dv, $correo, $habilitado, $nombre, $tipo)
    {
        $rut_concatenado = $run . "-" . $dv;
        $aid = env('AID', "");
        $aid_rol = $_ENV['sso_rol_defecto']; // temporal mientras se construye servicio
        $aid_id_app = $_ENV ['sso_id_app']; // temporal mientras se construye servicio
        $aid_nombre = $_ENV ['sso_nombre']; // temporal mientras se construye servicio

        try {
            $ws_sso = new SoapClient(env('SSO_WS_URL'), array('cache_wsdl' => WSDL_CACHE_NONE));
            if ($ws_sso) {
                $ws_sso->__setLocation(($_ENV['sso_ws_url']));
                $arreglo3 = array('ID' => $aid_rol, 'Id_Aplicacion' => $aid_id_app, 'Nombre' => $aid_nombre);
                $arreglo2 = array('Rol' => $arreglo3);
                $arreglo1 = array('Correo' => $correo, 'Habilitado' => $habilitado, 'Nombre' => $nombre, 'Roles' => $arreglo2, 'RUT' => $rut_concatenado, 'Tipo' => $tipo);
                $consulta_soap = $ws_sso->CrearUsuario(array('Usuario' => $arreglo1, 'AID' => $aid)); // se envia segun estructura recepcion SOAP ( 3 subarreglos)
                $consulta_soap = $consulta_soap->CrearUsuarioResult;
                if ($consulta_soap->Estado == "1") {
                    // "Creado con exito";
                    $valor = 1;
                } else {
                    // "No Creado";
                    $valor = 0;
                }
            }
        } catch (Exception $e) {
            //$q = DB::getQueryLog();
            Log::error("getSSO_Login -- SSOSoapCliente.php " . $e->getMessage());
            return -2;
        }
        return ($valor);
    }

    public static function getSSO_DeshabilitarUsuario($run, $dv)
    {
        $rut_concatenado = $run . "-" . $dv;
        $aid = env('AID', "");
        try {
            $ws_sso = new SoapClient(env('SSO_WS_URL'), array('cache_wsdl' => WSDL_CACHE_NONE));
            if ($ws_sso) {
                $ws_sso->__setLocation(($_ENV['sso_ws_url']));
                $consulta_soap = $ws_sso->DeshabilitarUsuario(array('rut' => $rut_concatenado, 'AID' => $aid)); // se envia segun estructura recepcion SOAP ( 1 subarreglos)
                $consulta_soap = $consulta_soap->DeshabilitarUsuarioResult;
                if ($consulta_soap->Estado == '1') {
                    // "Resultado exitoso";
                    $valor = self::RESULTADO_OK;
                } else {
                    // "Resultado NO exitoso";
                    $valor = self::RESULTADO_NO_OK;
                }
            }

        } catch (Exception $e) {
            //$q = DB::getQueryLog();
            Log::error("getSSO_DeshabilitarUsurio -- SSOSoapCliente.php " . $e->getMessage());
            return self::RESULTADO_EXCEPTION;
        }
        return ($valor);
    }

    public static function getSSO_EliminarUsuario($run, $dv)
    {
        $rut_concatenado = $run . "-" . $dv;
        $aid = env('AID', "");
        try {
            $ws_sso = new SoapClient(env('SSO_WS_URL'), array('cache_wsdl' => WSDL_CACHE_NONE));
            if ($ws_sso) {
                $ws_sso->__setLocation(($_ENV['sso_ws_url']));
                $consulta_soap = $ws_sso->EliminarUsuario([
                    'rut' => $rut_concatenado,
                    'AID' => $aid
                ]); // se envia segun estructura recepcion SOAP ( 1 subarreglos)
                $consulta_soap = $consulta_soap->EliminarUsuarioResult;
                if ($consulta_soap->Estado == '1') {
                    // "Resultado exitoso";
                    $valor = self::RESULTADO_OK;
                } else {
                    // "Resultado NO exitoso";
                    $valor = self::RESULTADO_NO_OK;
                }
            }
        } catch (Exception $e) {
            //$q = DB::getQueryLog();
            Log::error("getSSO_EliminarUsuario -- SSOSoapCliente.php " . $e->getMessage());
            return self::RESULTADO_EXCEPTION;
        }
        return ($valor);
    }

    public static function getSSO_HabilitarUsuario($run, $dv)
    {
        $rut_concatenado = $run . "-" . $dv;
        $aid = env('AID', "");
        try {
            $ws_sso = new SoapClient(env('SSO_WS_URL'), array('cache_wsdl' => WSDL_CACHE_NONE));
            if ($ws_sso) {
                $ws_sso->__setLocation(($_ENV['sso_ws_url']));
                $consulta_soap = $ws_sso->HabilitarUsuario(array('rut' => $rut_concatenado, 'AID' => $aid)); // se envia segun estructura recepcion SOAP ( 1 subarreglos)
                $consulta_soap = $consulta_soap->HabilitarUsuarioResult;
                if ($consulta_soap->Estado == '1') {
                    $var = "Resultado exitoso";
                    $valor = self::RESULTADO_OK;
                } else {
                    $var = "Resultado NO exitoso";
                    $valor = self::RESULTADO_NO_OK;
                }
            }

        } catch (Exception $e) {
            //$q = DB::getQueryLog();
            Log::error("getSSO_HabilitarUsuario -- SSOSoapCliente.php " . $e->getMessage());
            return self::RESULTADO_EXCEPTION;
        }
        return ($valor);
    }



    public static function getSSO_Login($run, $dv, $pass)
    {
        $rut_concatenado = $run . "-" . $dv;
        $aid = env('AID', "");
        try {
            $ws_sso = new SoapClient(env('SSO_WS_URL'), array('cache_wsdl' => WSDL_CACHE_NONE));
            if ($ws_sso) {
                $ws_sso->__setLocation((env('SSO_WS_URL')));
                $consulta_soap = $ws_sso->Login([
                    'rut' => $rut_concatenado,
                    'clave' => $pass,
                    'AID' => $aid
                ]);
                if ($consulta_soap) {
                    $consulta_soap = $consulta_soap->LoginResult;
                    if ($consulta_soap->Estado == "1") {
                        // $var = "login correcto";
                        $valor = self::RESULTADO_OK;
                    } else {
                        // $var = "login incorrecto";
                        $mensaje = $consulta_soap->Detalle;
                        $valor = self::RESULTADO_NO_OK;
                    }
                }
            }
        } catch (Exception $e) {
            //$q = DB::getQueryLog();
            Log::error("getSSO_Login -- SSOSoapCliente.php " . $e->getMessage());
            return self::RESULTADO_EXCEPTION;
        }
        return ($valor);
    }

    public static function getSSO_BuscarUsuario2($run, $dv)
    {
        $rut_concatenado = $run . "-" . $dv;
        $aid = env('AID', "");
        try {
            $ws_sso = new SoapClient(($_ENV['sso_ws_url']), array('cache_wsdl' => WSDL_CACHE_NONE));
            if ($ws_sso) {
                $ws_sso->__setLocation(($_ENV['sso_ws_url']));
                $consulta_soap = $ws_sso->BuscarUsuario(array('rut' => $rut_concatenado, 'AID' => $aid));
                if ($consulta_soap) {
                    $error = null;
                    $consulta_soap = $consulta_soap->BuscarUsuarioResult;
                    $rCantidad = (int)$consulta_soap->Cantidad;
                    if ($rCantidad == 1) {
                        //
                        // cuando hay un solo registro en LDAP
                        //

                        $resultado_ws = $consulta_soap->Resultado;
                        $resultado_ws = $resultado_ws->Usuario;
                        if (empty ($consulta_soap->Resultado)) {
                            $nombre = " ";
                            $error = SSOSoapCliente::RESULTADO_OK;
                        } else {
                            $nombre = $resultado_ws->Nombre;
                        }
                        if (empty($resultado_ws->Correo)) {
                            $correo = " ";
                            $error = SSOSoapCliente::NO_TIENE_CORREO;
                        } else {
                            $correo = $resultado_ws->Correo;
                            if (!self::validarEmail($correo)) {
                                $error = SSOSoapCliente::CORREO_NO_VALIDO;
                            }
                        }
                        if (empty($resultado_ws->Habilitado)) {
                            $nombre = " ";
                            $error = SSOSoapCliente::RESULTADO_OK;
                        } else {
                            $habilitado = $resultado_ws->Habilitado;
                        }
                        if (empty($resultado_ws->Tipo)) {
                            $tipo = " ";
                            $error = SSOSoapCliente::RESULTADO_OK;
                        } else {
                            $tipo = $resultado_ws->Tipo;
                        }
                        $path = $resultado_ws->Path;
                        $resultado = array("estado" => self::USUARIO_ENCONTRADO, "nombres" => $nombre, "correo" => $correo, "habilitado" => $habilitado, "path" => $path, "tipo" => $tipo, "error" => $error);
                    } elseif ($rCantidad > 1) {
                        //
                        // cuando hay mas de un registro en LDAP, es decir DOBLE
                        //
                        $resultado_ws = $consulta_soap->Resultado;
                        $resultado_ws = $resultado_ws->Usuario;
                        $error = self::ERROR_RESULTADO_DOBLE;
                        $resultado_ws = $consulta_soap->Resultado;
                        $resultado_ws = $resultado_ws->Usuario;
                        $nombre = (empty ($resultado_ws->Nombre)) ? " " : $resultado_ws->Nombre;
                        $correo = (empty($resultado_ws->Correo)) ? " " : $resultado_ws->Correo;
                        $habilitado = (empty($resultado_ws->Habilitado)) ? " " : $resultado_ws->Habilitado;
                        $tipo = (empty ($resultado_ws->Tipo)) ? " " : $resultado_ws->Tipo;
                        $path = (empty ($resultado_ws->Path)) ? " " : $resultado_ws->Path;
                        $resultado = array("estado" => self::USUARIO_ENCONTRADO, "nombres" => $nombre, "correo" => $correo, "habilitado" => $habilitado, "path" => $path, "tipo" => $tipo, "error" => $error);
                    } else {
                        $error = 0;
                        $resultado = array("estado" => self::USUARIO_NO_ENCONTRADO, "error" => $error);
                    }
                }
            }
        } catch (Exception $e) {
            //$q = DB::getQueryLog();
            Log::error("SSOSoapCliente.php -- getSSO_BuscarUsuario2 " . $e->getMessage());
            return self::RESULTADO_EXCEPTION;
        }
        return ($resultado);
    }

    public static function validarEmail($email)
    {
        if (!preg_match('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^', $email)) {
            return false;
        } else {
            return true;
        }
    }

}
