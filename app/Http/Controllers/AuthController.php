<?php

namespace App\Http\Controllers;

use App\Classes\SSOSoapCliente;
use App\Constantes\Constante;
use App\Models\Perfil;
use App\Models\Usuario;
use App\Models\UsuariosAuth;
use App\Models\UsuariosUsrPerfilRol;
use DateTime;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class AuthController
{

    public function checkSession()
    {
        if (!Auth::check()) {
            return "<script>alert('Su session ha expirado por inactividad'); window.location = \"/\";</script>";
        }
        if (Session::get('session_rut_usuario') == null) {
            return "<script>alert('Su session ha expirado por inactividad'); window.location = \"/\";</script>";
        }
    }

    public function showLogin(Request $request)
    {
        $ambienteDebug = env('ambienteDebug');

        if ($ambienteDebug || env('VIA_TOKEN_SSO') == false) {
            return View::make('cuenta.ingresar');
        }
        return $this->loginsso($request);
    }

    public function normalizaTexto($cadena)
    {
        $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞ
                        ßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuy
                        bsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        $cadena = strtolower($cadena);
        return utf8_encode($cadena);
    }

    public function postLogin(Request $request, $token = null)
    {
        $ambienteDebug = env('ambienteDebug');
//        $viaTokenSSO = env('VIA_TOKEN_SSO');
        $entroSSO = true;
        $validaciones_ok = 0;
        $ssoResponseLogin = -99;

        if (!$token) {
            $entroSSO = false;
            if ($request->input('run') == "" || $request->input('password') == "") {
                //Datos en blanco
                return View::make('cuenta.index')
                    ->with('alerta', 'Debe ingresar RUN y Contraseña.')
                    ->with($request->all());
            }

            $runDvConPuntosYGuion = trim($request->input('run'));
            $runDvSinPuntosNiGuion = str_replace(['.', '-', ' '], '', $runDvConPuntosYGuion);
            $dv = strtoupper(substr($runDvSinPuntosNiGuion, -1));
            $runSinPuntosNiGuionNiDv = substr($runDvSinPuntosNiGuion, 0, -1);

            if (strtoupper($dv) != strtoupper(Utiles::dv($runSinPuntosNiGuionNiDv))) {
                return View::make('cuenta.ingresar')
                    ->with('alerta', 'El Dígito Verificador es incorrecto.')
                    ->with($request->all());
            }

            $run = $runSinPuntosNiGuionNiDv;
            $dv_prueba = $dv;
            $passwd2 = $request->input('password');

            $usuariosAutorizados = UsuariosAuth::where('rut', $run)
                ->first();
            if (is_object($usuariosAutorizados)) {
                if (Hash::check($passwd2, $usuariosAutorizados->password)) {
                    $validaciones_ok = 1;
                } else {
                    return View::make('cuenta.ingresar')
                        ->with('alerta', 'Usuario o contraseña no coincide.');
                }
            } else {
                return View::make('cuenta.ingresar')
                    ->with('alerta', 'Usuario no Autorizado.');
            }
        } else {
            $ssoResponseAutorizar = SSOSoapCliente::getSSO_Autorizar($token);
            if ($ssoResponseAutorizar['Estado'] == SSOSoapCliente::RESULTADO_NO_OK) {
                return $this->logOut($request);
            }
            $temp = explode('-', $ssoResponseAutorizar['rut']);
            $run = $temp[0];
            $dv_prueba = $temp[1];
            $ssoResponseLogin = SSOSoapCliente::RESULTADO_OK;
        }

        if ($ssoResponseLogin == SSOSoapCliente::RESULTADO_OK && $validaciones_ok === 0) {
            // se debe evaluar la caducidad para validar si esta habilitado
            $ssoResponseBuscarUsuario = SSOSoapCliente::getSSO_BuscarUsuario($run, $dv_prueba);
            if ($ssoResponseBuscarUsuario["habilitado"] == true) { // en caso de Login exitoso, se comprueba cuenta habilitada.
                $validaciones_ok = 1;
            } //----------------------- Fin Validacion de caducidad ----------------------
            elseif ($ssoResponseBuscarUsuario["habilitado"] == false) {
                $validaciones_ok = 0;
                return View::make('cuenta.ingresar')
                    ->with('alerta', 'Su cuenta no esta habilitada.')
                    ->with($request->all());
            } else {
                return View::make('cuenta.ingresar')
                    ->with('alerta', 'No existe un perfil asignado en el aplicativo.')
                    ->with($request->all());
            }
        }

        if ($ssoResponseLogin == SSOSoapCliente::RESULTADO_NO_OK && $validaciones_ok === 0) {
            $ssoResponseBuscarUsuario = SSOSoapCliente::getSSO_BuscarUsuario($run, $dv_prueba);
            if ($ssoResponseBuscarUsuario["estado"] == SSOSoapCliente::USUARIO_NO_ENCONTRADO) {  // en caso de Login NO exitoso.
                $validaciones_ok = 0;
                return View::make('cuenta.ingresar')
                    ->with('alerta', 'Usuario no encontrado.')
                    ->with($request->all());
            }

            if (!$ssoResponseBuscarUsuario["habilitado"]) { // en caso de Login NO exitoso.
                $validaciones_ok = 0;
                return View::make('cuenta.ingresar1')
                    ->with('alerta', 'Su cuenta no esta habilitada.')
                    ->with($request->all());
            }

            if (($ssoResponseBuscarUsuario["estado"] == SSOSoapCliente::USUARIO_ENCONTRADO) &&
                ($ssoResponseBuscarUsuario["habilitado"]) && (!$ambienteDebug)
            ) {
                // en caso de error de contrasena.
                $validaciones_ok = 0;
                return View::make('cuenta.ingresar')
                    ->with('alerta', 'Su contraseña es incorrecta.')
                    ->with($request->all());
            } elseif (($ssoResponseBuscarUsuario["estado"] == SSOSoapCliente::USUARIO_ENCONTRADO) &&
                ($ssoResponseBuscarUsuario["habilitado"]) && ($ambienteDebug)
            ) {
                // modo DEBUG
                $validaciones_ok = 1;
            }
        }
        // -------------------------------------------------------------------------------------

        if ($validaciones_ok == 1) {
            try {
                $usuariosAutorizados = UsuariosAuth::where('rut', $run)->first();

                if (is_object($usuariosAutorizados)) {
                    if (Hash::needsRehash($usuariosAutorizados->password) && isset($passwd2)) {
                        $usuariosAutorizados->password = Hash::make($passwd2);
                    }
                    $usuariosAutorizados->ultimo_acceso = new datetime();

                    $usuariosAutorizados->save();
                    Auth::login($usuariosAutorizados);
                    Session::put('heskUserRut', $run);
                    Session::put('heskUserEstado', $usuariosAutorizados->estado);
                    Session::put('heskUserNombre', $usuariosAutorizados->nombre);
                    Session::put('heskUserPerfil', $usuariosAutorizados->perfil);
                    Session::put('heskUserCargo', $usuariosAutorizados->cargo);
                    Session::put('entroSSO', $entroSSO);
                    // Inicio Busca el Rol -> Solo esta funcionando este Rol, por ahora 25-06-2021
                        $Data_rol = UsuariosUsrPerfilRol::where('id_usr',$usuariosAutorizados->id)->where('estado',1)->get();
                        $rol = count($Data_rol) > 0 ? $Data_rol : null;
                    // Fin Rol
                    Session::put('heskUserRol', $rol);
                    return Redirect::to('/escritorio');
                } else {
                    return View::make('cuenta.ingresar')
                        ->with('alerta', 'Usuario no Autorizado.');
                }
            } catch (Exception $ex) {
                return View::make('cuenta.ingresar')
                    ->with('alerta', 'Usuario no Autorizado.');
            }
        } else {
            return View::make('cuenta.ingresar')
                ->with('alerta', 'Usuario no Autorizado.');
        }
    }

    public function logOut(Request $request)
    {
        $ambienteDebug = env('ambienteDebug', false);
//        $viaTokenSSO = env('VIA_TOKEN_SSO', true);
        $viaTokenSSO = Session::get('entroSSO');
        $ssoSalir = env('sso') . "/libre/salir.aspx";
        Auth::logout();
        Session::flush();

        if ($ambienteDebug || !$viaTokenSSO) {
            return Redirect::to('/');
        } else {
            return Redirect::to($ssoSalir);
        }
    }

    public function inicio()
    {
        $lstUsuarios = Usuario::all();
        $lstPerfil = Perfil::listarPerfilesValidos();
        return View::make('cuenta')
            ->with('lstUsuarios', $lstUsuarios)
            ->with('lstPerfil', $lstPerfil);
    }

    public function postRecuperar2()
    {
        if (env('ACTIVACION_RECAPTCHA')) {
            $secretKey = env('CLAVE_PRIVADA_RECAPTCHA');
            $token_recaptcha = Input::get('g-recaptcha-response');
            $ip = $_SERVER['SERVER_ADDR'];

            $config_captcha = array(
                "secret" => $secretKey,
                "response" => $token_recaptcha,
                "remoteip" => $ip
            );
            $curlConfig = array(CURLOPT_URL => "https://www.google.com/recaptcha/api/siteverify",
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $config_captcha);
            $ch = curl_init();
            curl_setopt_array($ch, $curlConfig);
            $response_recaptcha = curl_exec($ch);
            // decode json response
            $response_recaptcha = json_decode($response_recaptcha, true); // associative array

            if (!isset($response_recaptcha['success']) || $response_recaptcha['success'] == false) {
                Log::error(array('error recaptcha' => $response_recaptcha));
                return View::make('cuenta.ingresar')
                    ->with('alerta', 'Error: Error inesperado del captcha.')
                    ->withInput(Input::all());
            }
            //score entre 0.5 y 1 validamos
            ($response_recaptcha['score'] >= 0.3) ? $validate_recaptcha = true : $validate_recaptcha = false;

            if (!$validate_recaptcha) {
                Log::info('Intento de acceso via clave run, no hizo match con validacion [score obtenido]---> ' . $response_recaptcha['score']);
                return View::make('cuenta.ingresar')
                    ->with('alerta', 'Error: Acceso no autorizado, cod. error 01B-0T.')
                    ->withInput(Input::all());
            }
        }

        if (Input::get('run') == "" || Input::get('mail') == "") {
            return View::make('cuenta.ingresar')
                ->with('alerta', 'Debe ingresar los elementos solicitados RUN y E-MAIL para poder recuperar la contraseña.')
                ->withInput(Input::all());
        }

        $validaRun = Utiles::validaRUN(Input::get('run'));
        if ($validaRun == false) {
            return View::make('cuenta.ingresar')
                ->with('alerta', 'El Digito Verificador es incorrecto.')
                ->withInput(Input::all());
        }

        $tmp = explode('-', Input::get('run'));
        $rut_ws_sso = str_replace('.', '', $tmp[0]);
        $dv_ws_sso = strtoupper($tmp[1]);

        $hoy = date('Y-m-j H:i:00');
        $emailUsuario = Input::get('mail');
        $oUsuario = Usuario::where('per_run', $rut_ws_sso)->first();
        $emailUsuario = strtolower(trim($emailUsuario));
        $mailBD = count($oUsuario) >= 1 ? strtolower(trim($oUsuario->mail)) : null;
        if (count($oUsuario) && ($emailUsuario == $mailBD)) {
            if ($hoy > (date($oUsuario->fecha_caducidad))) {
                return View::make('cuenta.ingresar')
                    ->with('alerta', 'La cuenta se encuentra caducada, por favor comuniquese con el Encargado Comunal o Nivel Central')
                    ->withInput(Input::all());
            }
            $fechaCaducaNueva = strtotime(' + 30 day', strtotime($hoy));    // se anaden 30 dias mas.
            $fechaCaducaNueva = date('Y-m-j', $fechaCaducaNueva);
            // ---- Generacion de clave ---
            $passwd_generada = Utiles::generaContrasena($rut_ws_sso);
            $oUsuario->password = md5($passwd_generada);
            // ------Fin generacion clave------------
            $oUsuario->fecha_caducidad = $fechaCaducaNueva;
            $oUsuario->rut_usuario = $rut_ws_sso;
            $oUsuario->intentos_login = UsuarioController::CAMBIAR_PASSWORD_LOGIN;
            $oUsuario->save();
            /*
             * Generacion de envio de mail con la contrasena.
             */
            EnvioMailRest::enviarClave($passwd_generada, $mailBD, false, true);
            return View::make('cuenta.ingresar')
                ->with('alerta', 'La nueva credencial de acceso ha sido enviado al correo electrónico ')
                ->withInput(Input::all());

        } else {
            return View::make('cuenta.ingresar')
                ->with('alerta', 'El Run o E-mail ingresado no existe en nuestros registros')
                ->withInput(Input::all());
        }
    }

    public function postRecuperar()
    {
        try {
            if (Input::get('run') == "") {
                return View::make('cuenta.ingresar')
                    ->with('alerta', 'Debe ingresar RUN para recuperar la contraseña.')
                    ->withInput(Input::all());
            }
            if (Input::get('dv') != Utiles::dv(Input::get('run'))) {
                return View::make('cuenta.ingresar')
                    ->with('alerta', 'El Digito Verificador es incorrecto.')
                    ->withInput(Input::all());
            }
            $rut_ws_sso = Input::get('run');
            $dv_ws_sso = Input::get('dv');
            $buscarResponse = SSOSoapCliente::getSSO_BuscarUsuario($rut_ws_sso, $dv_ws_sso);
            $oUsuario = Usuario::where('per_run', $rut_ws_sso)->first();
            if ($buscarResponse ["estado"] == 'encontrado' and $buscarResponse ['tipo'] != "INTERNO" and $oUsuario == true) {
                // ---- Generacion de clave ---
                $passwd_ws_sso = Utiles::generaContrasena($rut_ws_sso);
                $oUsuario->password = (password_hash($passwd_ws_sso, PASSWORD_DEFAULT)) . (rand(100, 50000));
                // ------Fin generacion clave------------
                $oUsuario = Usuario::where('username', str_replace('.', '', $rut_ws_sso))
                    ->first();
                $oUsuario->rut_usuario = $rut_ws_sso;
                $oUsuario->intentos_login = UsuarioController::CAMBIAR_PASSWORD_LOGIN;
                $oUsuario->save();
                // el metodo de crear usuario se utiliza obligatoriamente para realizar el envio de correo electronicos,
                // ya que mediante el metodo Cambiar Clave no realiza el envio de contrasenas via correo electronico.
                $contrasena = SSOSoapCliente::getSSO_CrearUsuario($rut_ws_sso, $dv_ws_sso, $passwd_ws_sso, $buscarResponse['correo'], $buscarResponse['habilitado'], $buscarResponse['nombres'], $buscarResponse['tipo']);
                $contrasena = SSOSoapCliente::getSSO_CambiarClave($rut_ws_sso, $dv_ws_sso, $passwd_ws_sso);
                $asignar = SSOSoapCliente::getSSO_AsignarRoles($rut_ws_sso, $dv_ws_sso);
                if (($contrasena ['valor'] == SSOSoapCliente::RESULTADO_OK) && ($asignar == SSOSoapCliente::RESULTADO_OK)) {
                    return View::make('cuenta.ingresar')
                        ->with('alerta', 'La nueva credencial de acceso ha sido enviado al correo electrónico ')
                        ->withInput(Input::all());
                } else {
                    return View::make('cuenta.ingresar')
                        ->with('alerta', 'El Run ingresado no cuenta con permisos necesarios')
                        ->withInput(Input::all());
                }
            } elseif ($buscarResponse ['tipo'] == "INTERNO") {
                return View::make('cuenta.ingresar')
                    ->with('alerta', 'Usuario del tipo interno!, no puede resetear la contrasena')
                    ->withInput(Input::all());
            } else {
                return View::make('cuenta.ingresar')
                    ->with('alerta', 'El Run ingresado no cuenta con credencial de acceso . ')
                    ->withInput(Input::all());
            }
        } catch (Exception $e) {
            Log::error($e);
            return View::make('cuenta.ingresar')
                ->with('alerta', 'Ha ocurrido un error inesperado, intente más tarde . ' . $e)
                ->withInput(Input::all());
        }
    }

    public function genPassword($min = 6, $max = 8)
    {
        $vocales = array('a', 'e', 'i', 'o', 'u');
        $consonantes = array('b', 'c', 'd', 'f', 'g', 'j', 'l', 'm', 'n', 'p', 'r', 's', 't');
        $tamano = intval(rand($min, $max));
        $actual = intval(rand(1, 2));
        $nombre = '';
        for ($x = 0; $x < $tamano; $x++) {
            if ($actual == 0) {
                $actual = 1;
                $pos = rand(0, count($vocales) - 1);
                $nombre .= $vocales[$pos];
            } else {
                $actual = 0;
                $pos = rand(0, count($consonantes) - 1);
                $nombre .= $consonantes[$pos];
            }
        }
        return ucfirst($nombre);
    }

    public function cambiarPassword()
    {
        return View::make("cuenta.cambioPassword");
    }

    public function loginsso(Request $request)
    {
        $t = $request->input("t");
        if (!$t) {
            return Redirect::away(env('sso') . "?AID=" . env('AID'));
        } else {
            return $this->postLogin($request, $t);
        }
    }

    public function confirmaclaveunica()
    {
        Session::forget('claveunica_token');

        $authConfig = [
            'client_info' => [
                'client_id' => env('CLAVE_UNICA_CLIENT_ID'),
                'redirect_uri' => env('CLAVE_UNICA_URI'),
                'authorization_endpoint' => env('CLAVE_UNICA_AUTH_ENDPOINT'),
                'token_endpoint' => env('CLAVE_UNICA_TOKEN_ENDPOINT'),
                'user_info_endpoint' => env('CLAVE_UNICA_USER_INFO_ENDPOINT'),
                'authentication_info' => [
                    'method' => env('CLAVE_UNICA_METHOD'),
                    'params' => [
                        'client_secret' => env('CLAVE_UNICA_CLIENT_SECRET')
                    ]
                ]
            ]
        ];

        if (!env('PERMITE_INGRESO_POR_CLAVE_UNICA')) {
            Session::put('claveunica_token', env('CLAVE_UNICA_TOKEN_FALSO'));
            return $this->confirmaclaveunicacallback();
//            return Redirect::to(URL::previous());
//                ->withErrors(array('Login usando Clave Unica no esta permitido por el momento . '));
        } else {
            $flow = new Basic($authConfig);
            if (!isset($_GET['redirect'])) {
                try {
                    $uri = $flow->getAuthorizationRequestUri('openid run');
                    return Redirect::to($uri);
                } catch (\Exception $ex) {
                    Log::error($ex);
                    return Redirect::to(URL::previous())
                        ->withErrors(array('Advertencia: El servicio de Claveúnica no se encuentra disponible en este momento, por favor intente más tarde o utilice el acceso a través de RUN y Fecha de Nacimiento.'));
                }
            } else {
                try {
                    $userInfo = $flow->process();
                } catch (\Exception $e) {
                    Log::error($e);
                    return Redirect::to(URL::previous())
                        ->withErrors(array('Advertencia: El servicio de Claveúnica no se encuentra disponible en este momento, por favor intente más tarde o utilice el acceso a través de RUN y Fecha de Nacimiento.'));
                }
            }
        }
    }

    public function confirmaclaveunicacallback()
    {
        $token = null;
        $authConfig = [
            'client_info' => [
                'client_id' => env('CLAVE_UNICA_CLIENT_ID'),
                'redirect_uri' => env('CLAVE_UNICA_URI'),
                'authorization_endpoint' => env('CLAVE_UNICA_AUTH_ENDPOINT'),
                'token_endpoint' => env('CLAVE_UNICA_TOKEN_ENDPOINT'),
                'user_info_endpoint' => env('CLAVE_UNICA_USER_INFO_ENDPOINT'),
                'authentication_info' => [
                    'method' => env('CLAVE_UNICA_METHOD'),
                    'params' => [
                        'client_secret' => env('CLAVE_UNICA_CLIENT_SECRET')
                    ]
                ]
            ]
        ];

        if (env('PERMITE_INGRESO_POR_CLAVE_UNICA') == false) {
            $token = env('CLAVE_UNICA_TOKEN_FALSO');
        } else {
            $flow = new Basic($authConfig);
            $token = $flow->getAccessToken($_GET['code']);
            Session::put('claveunica_token', $token);
            $infoPersonal = $flow->getUserInfo($token);
            $contentResponse = $flow->getTokenDispatcher()->getLastHttpResponse()->getContent();
        }

        if ($token != NULL) {
            //caso de exito validado con Clave Unica
            Session::put('pasomodalconfirmar', "true");
            return Redirect::to("cartola-ciud");
        } else {
            return Redirect::to('login')
                ->withErrors(array('Utilizó un enlace que requiere autenticación. Intente nuevamente.'));
        }
    }

}
