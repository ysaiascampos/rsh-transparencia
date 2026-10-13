<?php

namespace App\Http\Controllers;

use Exception;
use PDF;
use App\Models\Cargos;
use App\Models\Plataforma;
use App\Models\Dependencia;
use Illuminate\Http\Request;
use App\Models\UsuarioPerfil;
use App\Constantes\Constante;
use App\Exports\EscritorioExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

class ConsultasEscritorioController extends Controller
{
    public function show()
    {
        $subJoin = DB::table('rsht_log_consultas')
            ->select(DB::raw('count(*) as reportes, rsht_log_consultas.run_solicitante AS run_consultas'))
            ->groupBy('run_solicitante');

        $lstConsultas = DB::table('rsht_usuarios_auth')
            ->leftJoinSub($subJoin, 'consultas', function ($join) {
                $join->on('rsht_usuarios_auth.rut', '=', 'consultas.run_consultas');
            })->get();

        $reportes = DB::table('rsht_log_consultas')->count();
        $externos = DB::table('rsht_log_consultas')
            ->wherenotin('Perfil', Constante::PERFILES_INTERNOS)
            ->count();
        $internos = DB::table('rsht_log_consultas')
            ->wherein('perfil', Constante::PERFILES_INTERNOS)
            ->count();
        $lstCargos = Cargos::all();
        $lstDependencias = Dependencia::all();
        $lstPerfiles = UsuarioPerfil::all();
        $dependenciaSeleccion = array();
        $cargoSeleccion = array();
        $perfilSeleccion = array();
        foreach ($lstCargos as $aux) {
            $cargoSeleccion[$aux->id_cargo] = $aux->nombre_cargo;
        }
        foreach ($lstDependencias as $aux) {
            $dependenciaSeleccion[$aux->id_dependencia] = $aux->nombre_dependencia;
        }
        foreach ($lstPerfiles as $perfil) {
            $perfilSeleccion[$perfil->id_perfil] = $perfil->nombre_perfil;
        }


        return View::make('transparencia.menu.escritorio')
            ->with('lstPerfil', $perfilSeleccion)
            ->with('lstCargo', $cargoSeleccion)
            ->with('lstDependencias', $dependenciaSeleccion)
            ->with('externos', $externos)
            ->with('internos', $internos)
            ->with('reportes', $reportes)
            ->with('lstConsultas', $lstConsultas);
    }

    public function pdfShow()
    {
        return View::make('PDF.base_pdf');
    }

    public function consultarRut(Request $request)
    {
        $run = $request->rut;
        $consulta = Plataforma::where('run', $run)->first();
        if (is_object($consulta)) {
            return Response::JSON(["encontrado" => true], 200);
        } else {
            return Response::JSON(["encontrado" => false], 200);
        }
    }

    public function descargaResultadosEscritorio(Request $request)
    {
        try {
            $input = $request->except('_token');
            $params = $request->all();

            $lstCargos = Cargos::all();
            $lstDependencias = Dependencia::all();
            $lstPerfiles = UsuarioPerfil::all();
            $subJoin = DB::table('rsht_log_consultas')
                            ->select(DB::raw('count(*) as reportes, rsht_log_consultas.run_solicitante AS run_consultas'))
                            ->groupBy('run_solicitante');
            $folios = DB::table('rsht_usuarios_auth')
                            ->leftJoinSub($subJoin, 'consultas', function ($join) {
                                $join->on('rsht_usuarios_auth.rut', '=', 'consultas.run_consultas');
            });

            $data['items'] = $folios->get();
            $data['cargos'] = $lstCargos;
            $data['dependencias'] = $lstDependencias;
            $data['perfiles'] = $lstPerfiles;

            $escritoExports = new EscritorioExport($data);
            return Excel::download($escritoExports, 'Resultados.xlsx');
        } catch (Exception $ex) {
            Log::error($ex->getMessage(),["Trace" => $ex->getTraceAsString(), "paramsRequest" => $params]);
            return Response::json(['errors' => ['Error inesperado']], 500);
        }
    }

}
