<?php

namespace App\Http\Controllers;


use App\Classes\SSOSoapCliente;
use App\Models\Cargos;
use App\Models\Dependencia;
use App\Models\Perfil;
use App\Models\UsuarioPerfil;
use App\Models\UsuariosAuth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Exception;


class MantenedorUsuariosController extends Controller
{
    const USUARIO_NO_ENCONTRADO = "no encontrado";
    const USUARIO_ENCONTRADO = "encontrado";

    public function show()
    {
        $lstUsuarios = UsuariosAuth::all();
        $lstCargos = Cargos::all()->pluck('nombre_cargo', 'id_cargo')->toArray();
        $lstDependencias = Dependencia::all()->pluck('nombre_dependencia', 'id_dependencia')->toArray();
        $lstPerfiles = UsuarioPerfil::all()->pluck('nombre_perfil', 'id_perfil')->toArray();

        return View::make('transparencia.usuarios.usuario_asignar')
            ->with('lstUsuarios', $lstUsuarios)
            ->with('lstCargos', $lstCargos)
            ->with('lstDependencias', $lstDependencias)
            ->with('lstPerfiles', $lstPerfiles) ;

    }

    public function buscarUsuario(Request $request)
    {
        $run = $request->input('rut');
        $dv = Utiles::dv($run);
        $buscarResponse = SSOSoapCliente::getSSO_BuscarUsuario($run, $dv);
        if ($buscarResponse['estado'] == self::USUARIO_ENCONTRADO) {
            $usuariosAutorizados = UsuariosAuth::where('rut', $run)->first();
            if (is_object($usuariosAutorizados)) {
                return redirect()->back()
                    ->with('message', 'ya existe usuario en el sistema');
            } else {
                $usuariosNuevo = new UsuariosAuth();
                $usuariosNuevo->id = UsuariosAuth::max('id') + 1;
                $usuariosNuevo->rut = $run;
                $usuariosNuevo->estado = SSOSoapCliente::getSSO_AsignarRoles($run, $dv);
                $usuariosNuevo->perfil = $buscarResponse['tipo'] == 'EXTERNO' ? 3 : 2;
                $usuariosNuevo->nombre = $buscarResponse['nombres'];
                $usuariosNuevo->password = Hash::make('Mds.2020');
                $usuariosNuevo->email = $buscarResponse['correo'];
                $usuariosNuevo->cargo = 2;
                $usuariosNuevo->dependencia = 1;
                $usuariosNuevo->save();
            }
        } else {
            return redirect()->back()
                ->with('message', 'usuario no encontrado en SSO');
        }
        return redirect()->back()
            ->with('message', 'La información se guardó exitosamente');
    }

    public function detalleUsuario(Request $request)
    {
        $rut = $request->input('rut');
        $data = ['error' => 'Error inesperado, intente nuevamente'];
        $status = 401;
        $dominiosValidos = explode(
            ',',
            env('DOMINIOS_CORREO', 'desarrollosocial.cl,desarrollosocial.gob.cl')
        );
        $lstCargos = Cargos::all()
            ->pluck('nombre_cargo', 'id_cargo')
            ->toArray();
        $lstDependencias = Dependencia::all()
            ->pluck('nombre_dependencia', 'id_dependencia')
            ->toArray();
        $lstPerfiles = UsuarioPerfil::all()
            ->pluck('nombre_perfil', 'id_perfil')
            ->toArray();

        if (!is_null($rut)) {
            $usuario = UsuariosAuth::where('rut', $rut)->first();
            if (is_object($usuario)) {
                $status = 200;
                $correo = explode('@', $usuario->email);
                $correoDominio = isset($correo[1]) ? $correo[1] : '';
                $data = [
                    'rut' => Utiles::ponePuntosNumero($usuario->rut),
                    'nombre' => $usuario->nombre,
                    'dependencia' => $usuario->dependencia,
                    'cargo' => $usuario->cargo,
                    'perfil' => $usuario->perfil,
                    'estado' => $usuario->estado,
                    'correoUsuario' => $correo[0],
                    'correoDominio' => $correoDominio,
                    'domains' => $dominiosValidos,
                    'dominioFuera' => !in_array(
                        $correoDominio,
                        $dominiosValidos
                    ),
                    'lstCargos' => json_encode($lstCargos),
                    'lstDependencias' => json_encode($lstDependencias),
                    'lstPerfiles' => json_encode($lstPerfiles)
                ];
            }
        }

        return Response::json($data, $status);
    }

    function actualizaUsuario(Request $request)
    {
        $rut = $request->input('rutModal');

        $usuarioCorreo = $request->input('usuarioCorreo1');
        $usuarioDominio = $request->input('usuarioCorreo2');

        $esDominioValido = in_array(
            $usuarioDominio,
            explode(',', env('DOMINIOS_CORREO', 'desarrollosocial.cl,desarrollosocial.gob.cl'))
        );

        $data = ["estado" => "fallido"];
        $status = 401;

        if (!is_null($rut) && $esDominioValido) {
            $rut = str_replace('.', '', $rut);
            $cargo = $request->input('usuarioCargo');
            $perfil = $request->input('usuarioPerfil');
            $dependencia = $request->input('usuarioDependencia');
            $estado = $request->input('usuarioEstado');

            $usuario = UsuariosAuth::where('rut', $rut)->first();

            if (is_object($usuario)) {
                if (!is_null($cargo)) {
                    $usuario->cargo = $cargo;
                }
                if (!is_null($perfil)) {
                    $usuario->perfil = $perfil;
                }
                if (!is_null($cargo)) {
                    $usuario->dependencia = $dependencia;
                }
                if (!is_null($cargo)) {
                    $usuario->estado = $estado;
                }

                $usuario->email = $usuarioCorreo . '@' . $usuarioDominio;

                if ($usuario->save()) {
                    $data = ["estado" => "ok"];
                    $status = 200;
                }
            }
        }

        Response::json($data, $status);
    }
}
