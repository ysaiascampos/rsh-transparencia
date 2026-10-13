<?php

namespace App\Http\Controllers;

use App\Models\Consultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeguimientoController extends Controller
{
    public function SeguimientoInterno(Request $request)
    {
        ini_set('max_execution_time', 80000);
        $runConsulta = $request->input('runConsulta');
        $fechai = $request->input('fechainicio');
        $fechaf = $request->input('fechafinal');

       // if( is_null($runConsulta) && is_null($fechai) && is_null($fechaf) ) {
       //     $consultas = null;
       // }else {
          // $consultas = Consultas::Join('vw_tbl_plataforma_cse_vig', 'run', '=', 'run_titular')
                $consultas = Consultas::Join('rsht_usuarios_auth', 'rut', '=', 'run_solicitante')
                ->Join('vw_personas', 'vw_personas.per_run', '=', 'run_titular')
                ->Join('rsht_tipo_reportes', 'id_reporte', '=', 'reporte')
                ->Join('rsht_tipo_razones', 'rsht_tipo_razones.id_razon', '=', 'razon_justificacion')
                ->whereIn('rsht_usuarios_auth.perfil', ['1', '2']);

            if (is_null($runConsulta) && is_null($fechai) && is_null($fechaf)) {
                $consultas->where(DB::RAW('ROWNUM'),'<=',50);
                $consultas->orderBy('acceso', 'desc');
            } else {
                $consultas->orderBy('acceso', 'desc');
            }

            if (!is_null($runConsulta)) {
                $runConsultaLimpio = Utiles::removerPuntoGuionyDV($runConsulta);
                $consultas->where('run_titular', $runConsultaLimpio);
            }

            if (!is_null($fechai)) {
                $consultas->where('acceso', '>=', DB::RAW("TO_DATE('$fechai 00:00:00','YYYY-MM-DD HH24:MI:SS')"));
            }

            if (!is_null($fechaf)) {
                $consultas->where('acceso', '<=', DB::RAW("TO_DATE('$fechaf 23:59:59','YYYY-MM-DD HH24:MI:SS')"));
            }
            $consultas = $consultas->get([
                'id_consulta',
                'run_titular',
                DB::RAW("vw_personas.per_nombres || ' ' || vw_personas.per_apellidopaterno  as nombre"),
                'rsht_tipo_razones.descripcion_razon',
                DB::RAW("nvl(texto_justificacion,'') as texto_justificacion"),
                'nombre_reporte',
                'acceso',
                'nombre_archivo'
            ]);
        //}
        return view('transparencia.menu.seguimiento_consulta_interno')
            ->with('lstConsultas', $consultas)
            ->with('runConsulta', $runConsulta)
            ->with('fechai', $fechai)
            ->with('fechaf', $fechaf);
    }
}
