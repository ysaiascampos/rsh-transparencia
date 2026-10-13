<?php

namespace App\Http\Controllers;

use App\Models\UsuarioPerfil;
use App\Models\UsuariosAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class MantenedorPerfilesController extends Controller
{
    public function show()
    {
        $lstUsuarios = UsuariosAuth::all();
        $lstPerfiles = UsuarioPerfil::all();
        $perfilSeleccion = array();
        foreach ($lstPerfiles as $perfil) {
            $perfilSeleccion[$perfil->id_perfil] = $perfil->nombre_perfil;
        }

        return View::make('transparencia.usuarios.perfil_roles')
            ->with('lstUsuarios', $lstUsuarios)
            ->with('lstPerfiles', $lstPerfiles)
            ->with('perfilSeleccion', $perfilSeleccion);

    }

//    public function listarPerfiles(Request $request)
//    {
//        $lstUsuarios = UsuariosAuth::all();
//        $lstPerfiles = UsuarioPerfil::all();
//        $perfilSeleccion = array();
//        foreach ($lstPerfiles as $perfil) {
//            $perfilSeleccion[$perfil->id_perfil] = $perfil->nombre_perfil;
//        }
//
//        return View::make('transparencia.usuarios.perfil_roles')
//            ->with('lstUsuarios', $lstUsuarios)
//            ->with('lstPerfiles', $lstPerfiles)
//            ->with('perfilSeleccion', $perfilSeleccion);
//
//    }
}
