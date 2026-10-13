<?php

namespace App\Http\Controllers;

use App\Constantes\Constante;
use App\Models\CsePlataformaVW;
use Illuminate\Support\Facades\Mail;
use PDF;
use Exception;
use ZipArchive;
use App\Classes\Enigma;
use App\Models\BaseCargas;
use App\Models\BaseCotizantes;
use App\Models\CsePlataforma;
use Carbon\Carbon;
use App\Models\TipoRazon;
use App\Models\Reportes;
use App\Models\Documento;
use App\Models\Consultas;
use App\Models\Sequence;
use App\Jobs\SendEmailJob;
use App\Models\UsuariosAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\Classes\PasswordProtectPDF;

class SolicitudController extends Controller
{

    //---blade de generar solicitud
    public function show()
    {
        $listadoRazones = TipoRazon::where('estado','=',1)->orderBy('orden', 'asc')->get();
        $listadoReportes = Reportes::all();
        return View::make('transparencia.menu.consulta_run')
            ->with('lstRazones', $listadoRazones)
            ->with('lstReportes', $listadoReportes);
    }

    //--------------
    public function store(Request $request)
    {
        ini_set('max_execution_time', 100000);
        $solicitud = new Consultas();
        $runLogueado = Session::get("heskUserRut");
        $cargoLogueado = Session::get("heskUserCargo");
        $perfilLogueado = Session::get("heskUserPerfil");
        $user = UsuariosAuth::where('rut', $runLogueado)->first();
        DB::beginTransaction();
        try {
            $idSolicitud = Sequence::nextValue('RSHT_LOG_CONSULTAS_ID_CONSULTA');
            $solicitud->id_consulta = $idSolicitud;
            //run que se consulta
            $solicitud->run_solicitante = $runLogueado;
            $solicitud->run_titular = $request->input('run');
            $solicitud->razon_justificacion = $request->input('razon');
            $solicitud->texto_justificacion = $request->input('txtrazon');
            $solicitud->reporte = $request->input('reporte');
            $solicitud->cargo = $cargoLogueado;
            $solicitud->perfil = $perfilLogueado;
            $solicitud->acceso = date('Y-m-d H:i:s');
            $solicitud->email = $user->email;
            $solicitud->nombre_archivo = $request->input('nombreConsulta') . '.pdf';
            $solicitud->save();

            $docSolicitud = new Documento();
            foreach ($request->files as $doc) {
                foreach ($doc as $index => $docReal) {
                    //genera variables
                    $file_name = date('YmdHis') . '.' . $docReal->getClientOriginalExtension();
                    $id_razon = json_decode($request->input("detalleDocs")[$index])->razon;
                    $txt_razon = json_decode($request->input("detalleDocs")[$index])->txt;
                    //guardar objeto
                    $docSolicitud->id_documento = Sequence::nextValue('RSHT_DOCUMENTOS_ID_DOCUMENTO_S');
                    $docSolicitud->nombre_original = $docReal->getClientOriginalName();
                    $docSolicitud->nombre_sistema = $file_name;
                    $docSolicitud->fecha_carga = date('Y-m-d H:i:s');
                    $docSolicitud->estado = Constante::ESTADO_HABILITADO;
                    $docSolicitud->texto_opcionalrazon = $txt_razon;
                    $docSolicitud->id_razon = $id_razon;
                    $docSolicitud->id_consulta = $idSolicitud;
                    if ($docSolicitud->save()) {
                        if (!File::exists(storage_path('Documentos'))) {
                            Storage::makeDirectory(storage_path('Documentos'));
                        }
                        $docReal->move(storage_path('Documentos'), $file_name);
                    }
                }
            }
            DB::commit();
            //manda a generar el reporte
            $hayPdf = self::generarReporte(
                $request->input('nombreConsulta'),
                $request->input('run'),
                $runLogueado,
                $request->input('reporte'));
            //fin generacion reporte
            //se hace el envio de correo
            if ($hayPdf) {
                $respEnvio = self::envioCorreo($solicitud->run_solicitante, $solicitud->run_titular, $solicitud);
                if(! $respEnvio ) {
                    log::info("No se envio correo, PDF sin data",
                        [
                            "solicitud" => $idSolicitud,
                            "solicitante" => $solicitud->run_solicitante,
                            "titular" => $solicitud->run_titular
                        ]);
                }
                return Response::JSON(
                    [
                        "respuesta" => true,
                        "id_solicitud" => Enigma::encrypt($idSolicitud),
                        "nombreArchivo" => $solicitud->nombre_archivo
                    ],
                    200
                );
            } else {
                return Response::JSON(['error' => 'Ocurrio un error al momento de la Generación del PDF.
                                        intentelo de nuevo ! y si persistes contacte al area de TI.'], 401);
            }
            //Fin Envio Correo

        } catch (Exception $ex) {
            log::info(
                "Error a guardar Solicitud id: " . $idSolicitud,
                [
                    "trace" => $ex->getTraceAsString(),
                    "mensaje" => $ex->getMessage()
                ]
            );
            DB::rollback();
            return Response::JSON(["respuesta" => false], 400);
        }
    }

    //--------------
    public function envioCorreo($solicitante, $titular, Consultas $solicitud)
    {
//        $updateSolicitud = Consultas::where('id_solicitud', $id)->first();
        $updateSolicitud = $solicitud;
        if (is_object($updateSolicitud)) {
            if (!is_null($updateSolicitud->email)) {
                //se marca 0 enviado a cola
                Session::put('heskUserEmail', $updateSolicitud->email);
                $details['id'] = $updateSolicitud->id_consulta;
                $updateSolicitud->correo_enviado = Constante::CORREO_ENVIADO_ENCOLAMIENTO;
//                $updateSolicitud->email = $updateSolicitud->email;
                $updateSolicitud->save();
                dispatch(new SendEmailJob($details));

                return true;
            } else {
                //marcamos el campo porque no se envio correo
                $updateSolicitud->correo_enviado = Constante::NO_POSEE_CORREO_NO_ENVIADO;
                $updateSolicitud->save();

                return true;
            }
        } else {
            log::error('Error no se encontro Solicitud');
            return false;
        }
    }

    //--------------
    public function verConsulta($idlogReporte)
    {
        $idlogReporte = Enigma::decrypt($idlogReporte);
        try {
            $sql = "SELECT
                        a.ID_CONSULTA ,
                        a.RUN_SOLICITANTE ,
                        a.RUN_TITULAR,
                        a.RAZON_JUSTIFICACION ,
                        b.NOMBRE_REPORTE ,
                        c.NOMBRE_CARGO ,
                        d.NOMBRE_PERFIL,
                        uauth.NOMBRE as NOMBRE_SOLICITANTE,
                        a.nombre_archivo
                    FROM
                        RSHT_LOG_CONSULTAS a
                    LEFT JOIN RSHT_TIPO_REPORTES b on( b.ID_REPORTE = a.REPORTE )
                    LEFT JOIN RSHT_TIPO_CARGOS c ON ( c.ID_CARGO = a.CARGO)
                    LEFT JOIN RSHT_USUARIOS_PERFILES d ON ( d.ID_PERFIL = a.PERFIL)
                    LEFT JOIN RSHT_USUARIOS_AUTH uauth on ( uauth.rut = a.run_solicitante)
                    WHERE a.ID_CONSULTA = " . $idlogReporte . "";
            $dataReporte = DB::select($sql);

            $sql2= "SELECT  per_run
                            ,per_dv
                            ,per_nombres
                            ,per_apellidopaterno
                            ,per_apellidomaterno
                    FROM VW_PERSONAS WHERE PER_RUN = ". $dataReporte[0]->run_titular;
            $personaExport= DB:: select($sql2);

            /*
             * $personaExport = $personaExport = DB::connection('oracle_export')
             ->table('personas')
             ->where('per_run', $dataReporte[0]->run_titular)
             ->first();
             $sqlDocs = "SELECT
                        a.ID_DOCUMENTO ,
                        a.NOMBRE_ORIGINAL ,
                        a.NOMBRE_SISTEMA
                        FROM RSHT_DOCUMENTOS a
                        WHERE
                        a.ESTADO = 1
                        AND
                        a.ID_CONSULTA = " . $idlogReporte;
            */

            $dataDocs = Documento::where('estado', '=', 1)->WHERE('id_consulta', $idlogReporte)->get();
            //DB::select($sqlDocs);

            return view('transparencia.menu.verConsulta')
                ->with('id', $idlogReporte)
                ->with('dataReporte', $dataReporte)
                ->with('dataDocs', $dataDocs)
                ->with('personaExp', $personaExport);

        } catch (Exception $ex) {
            $mensaje = ($ex instanceof RuntimeException) ? $ex->getMessage() : 'Error en la operación. Intente
                nuevamente.';
            return redirect()->back()->with('error', $mensaje);
        }
    }

    //----------------
    public function graphs()
    {
        //return view('transparencia.menu.graph');
        $runTitular="8966457";//"12181973";//
        $clave = substr($runTitular,-4);
        $ran = rand();
        return self::generarReporte($nombreReporte="Consulta_8966457_".$clave."_".$ran,
        $runTitular, $runSolicitante="8966457", $tipoReporte="1");
    }

    // function to generate PDF
    public function graphPdf()
    {
        $pdf = PDF::loadView('transparencia.menu.graph');
        $pdf->setOption('enable-javascript', true);
        $pdf->setOption('javascript-delay', 5000);
        $pdf->setOption('enable-smart-shrinking', true);
        $pdf->setOption('no-stop-slow-scripts', true);
        $pdfPath = storage_path('Reportes') . '/' . 'testfile.pdf';
        if (File::exists($pdfPath)) {
            unlink($pdfPath);
        }
        $pdf->Save($pdfPath);
        $protection = new PasswordProtectPDF($pdfPath,'123456','654321');
        $protection->output($pdfPath,'F');
//        return $pdf->download('graph.pdf');
    }

    public function descargarGenerado(Request $request, $idSolicitud)
    {
        $statusCode = 400;
        $data = [
            'data' => 'Error inesperado'
        ];
        $idSolicitud = Enigma::decrypt($idSolicitud);
        if (!is_numeric($idSolicitud) || is_null($idSolicitud)) {
            $statusCode = 400;
            $data = ['data' => 'Petició con parametros no validos.'];
        } else {
            // Cuando se trata de descarga del PDF generado de una solicitud
            $logConsulta = Consultas::where('id_consulta', $idSolicitud)
                ->first();
            if (is_object($logConsulta)) {
                try {
                    return self::descargaReporte($logConsulta);
                } catch (Exception $ex) {
                    Log::error("Error al descargar reporte de la solicitud " . $idSolicitud);
                    $data['data'] = "El reporte no esta presente para descargar en la plataforma";
                }
            } else {
                $statusCode = 400;
                $data = ['data' => 'No es posible ubicar el reporte para esta solicitud.'];
            }
        }
        return Response::json($data, $statusCode);
    }

    public function descargarAdjuntos(Request $request, $idSolicitud, $idAdjunto = null)
    {
        $statusCode = 400;
        $data = [
            'data' => 'Error inesperado'
        ];
        $idSolicitud = Enigma::decrypt($idSolicitud);
        if (!is_numeric($idSolicitud) || is_null($idSolicitud) || (!is_null($idAdjunto) && !is_numeric($idAdjunto))) {
            $statusCode = 400;
            $data = ['data' => 'Petició con parametros no validos.'];
        } else {
            if (is_null($idAdjunto)) {
                // Cuando se trata de descarga de todos los adjuntos de una solicitud no viene un id particular
                $docs = Documento::where('id_consulta', $idSolicitud)
                    ->get();
                if (is_object($docs)) {
                    try {
                        $nombre = $idSolicitud . "_" . date("Y-m-d") . ".zip";

                        $path = self::generarZipAdjuntos($nombre, $docs->toArray());
                        return self::descargarArchivo($path, $nombre, true);
                    } catch (Exception $ex) {
                        Log::error("Error al descargar zip para solicitud " . $idSolicitud);
                        $data['data'] = "Error al descargar zip para solicitud";
                    }
                } else {
                    $statusCode = 400;
                    $data = ['data' => 'No es posible ubicar adjuntos para esta solicitud.'];
                }
            } else {
                // Cuando se trata de un id particular solo se descarga ese adjunto de esa solicitud
                $doc = Documento::where('id_documento', $idAdjunto)
                    ->where('id_consulta', $idSolicitud)
                    ->first();

                if (!is_object($doc)) {
                    $statusCode = 400;
                    $data = ['data' => 'Información proporcionada no coincide con nuestros registros.'];
                } else {
                    try {
                        return self::descargaAdjuntos($doc);
                    } catch (Exception $ex) {
                        Log::error("Error al descargar documento adjunto " . $idAdjunto .
                            " de la solicitud " . $idSolicitud);
                        $data['data'] = "El documento no esta presente para descargar en la plataforma";
                    }
                }
            }
        }

        return Response::json($data, $statusCode);
    }

    public function descargaAdjuntos(Documento $doc)
    {
        $file = storage_path() . '/Documentos/' . $doc->nombre_sistema;

        return Response::download($file, $doc->nombre_original);
    }

    public function descargaReporte(Consultas $consulta)
    {
        $file = storage_path() . '/Reportes/' . $consulta->nombre_archivo;

        return Response::download($file, $consulta->nombre_archivo);
    }

    public static function generarZipAdjuntos($nombre, array $adjuntos)
    {
        $initialPath = storage_path() . DIRECTORY_SEPARATOR . "Documentos" . DIRECTORY_SEPARATOR;
        $path = $initialPath . $nombre;

        $zip = new ZipArchive();
        if ($zip->open($path, ZipArchive::OVERWRITE | ZipArchive::CREATE) !== true) {
            throw new Exception('Error al crear archivo ZIP para descarga');
        }

        $nombresArchivos = array_column($adjuntos, 'nombre_original');
        $repetidos = array_count_values($nombresArchivos);
        $contador = [];
        $archivosNoEncontrados = [];
        $archivosNoAgregados = [];

        foreach ($adjuntos as $archivo) {
            if (File::exists($initialPath . $archivo['nombre_sistema'])) {
                $nombreArchivo = null;
                if ($repetidos[$archivo['nombre_original']] > 1) {
                    $n = isset($contador[$archivo['nombre_original']]) ? $contador[$archivo['nombre_original']] : 1;
                    $info = pathinfo($archivo['nombre_original']);
                    $extension = isset($info["extension"]) ? '.' . $info["extension"] : '';
                    $nombreArchivo = ($n > 1) ?
                        $info["filename"] . "(" . $n . ")" . $extension : $archivo['nombre_original'];
                    $contador[$archivo['nombre_original']] = ++$n;
                } else {
                    $nombreArchivo = $archivo['nombre_original'];
                }
                if ($zip->addFile($initialPath . $archivo['nombre_sistema'], $nombreArchivo) !== true) {
                    $archivosNoAgregados[] = $archivo['nombre_original'];
                }
            } else {
                $archivosNoEncontrados[] = $archivo['nombre_sistema'];
            }
        }
        if (count($archivosNoEncontrados) > 0) {
            Log::info('Archivos no encontrados en el servidor: [' . implode(',', $archivosNoEncontrados) . ']');
        }
        if (count($archivosNoAgregados) > 0) {
            Log::info('Archivos que no se pudieron agregar: [' . implode(' ; ', $archivosNoAgregados) . ']');
        }
        if (count($adjuntos) == count($archivosNoEncontrados)) {
            throw new Exception('Los documentos no están disponibles en la plataforma.');
        }
        if (count($adjuntos) == count($archivosNoAgregados)) {
            throw new Exception('No se pudo cargar los documentos.');
        }
        if ($zip->numFiles > 0) {
            $zip->close();
        } else {
            throw new Exception('No se pudo cargar los documentos.');
        }
        return $path;
    }

    public function descargarArchivo($rutaArchivo, $nombreArchivo, $borrarDespuesDeEnviar = false)
    {
        $cabeceras = [
            'Content-Disposition' => 'attachment; filename=' . $nombreArchivo,
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ];
        return Response::download($rutaArchivo, $nombreArchivo, $cabeceras)
            ->deleteFileAfterSend($borrarDespuesDeEnviar);
    }

    //reporte
    public function generarReporte($nombreReporte, $runTitular, $runSolicitante, $tipoReporte)
    {
        $generoPDF = false;
        $solicitante = $runSolicitante;
        $titular = $runTitular;
        //informacion segun tipo de reporte
        switch ($tipoReporte) {
            //Reporte General 1
            case 1:
                $data = self::reporteGeneral($titular, $solicitante);
                break;
            default:
                $data = self::reporteGeneral($titular, $solicitante);
                break;
        }


        //return View::make('PDF.base_pdf')->with('data', $data);
        $pdf = null;
        if (!is_null($data)) {
            try {
                if (!File::exists(storage_path('Reportes'))) {
                    Storage::makeDirectory(storage_path('Reportes'));
                }

                $file_name = $nombreReporte . '.pdf';
                $pdfPath = storage_path('Reportes') . '/' . $file_name;
                if (File::exists(storage_path('Reportes') . '/' . $file_name)) {
                    unlink(storage_path('Reportes') . '/' . $file_name);
                }

                $pdf = PDF::loadView('PDF.base_pdf', ['data' => $data]);
                $pdf->setOption('enable-javascript', true);
                $pdf->setOption('javascript-delay', 5000);
                $pdf->setOption('enable-smart-shrinking', true);
                $pdf->setOption('no-stop-slow-scripts', true);
                //return $pdf->download('aeeeesd.pdf');
                $pdf->save($pdfPath);

                if (!$pdf) {
                    log::info("Falla en la Generación del Pdf",["data" => $data]);
                } else {
                    try {
                        $encPdf = new PasswordProtectPDF($pdfPath, substr($solicitante,-4), env('PDF_PW', '4KjV!}sve}>4)_f!Qc6_YFSk5gAcUa'));
                        $encPdf->output($pdfPath, 'F');
                    } catch (Exception $ex) {
                        log::error("Error al generar PDF",
                            [
                                "mensaje" => $ex->getMessage(),
                                "trace" => $ex->getTraceAsString(),
                                "nombreReporte" => $nombreReporte,
                                "titular" => $runTitular,
                                "solicitante" => $runSolicitante,
                                "tipoReporte" => $tipoReporte
                            ]);
                    }
                }
                $generoPDF = true;
            } catch (Exception $ex) {
                log::error("Falla en la Generación del Pdf",
                    [
                        "mensaje" => $ex->getMessage(),
                        "trace" => $ex->getTraceAsString(),
                        "nombreReporte" => $nombreReporte,
                        "titular" => $runTitular,
                        "solicitante" => $runSolicitante,
                        "tipoReporte" => $tipoReporte,
                        "infoReport" => $data
                    ]);
            }
        } else {
            $generoPDF = false;
            log::error("La data del PDF esta vacia, se suministra informacion de consulta:",
                [
                    "nombreReporte" => $nombreReporte,
                    "titular" => $runTitular,
                    "solicitante" => $runSolicitante,
                    "tipoReporte" => $tipoReporte
                ]);
        }
        return $generoPDF;
    }

    public function reporteGeneral($titular, $solicitante)
    {
        try {

            $data = self::cargaInicialReporte($titular,$solicitante);
            $peridoBusqueda = $data['periodoBusqueda']['periodo'];
            $ano = substr($peridoBusqueda, 0, 4);
            $mes = substr($peridoBusqueda, 4, 2);
            $dia = '01';
            $fecha_IMOT = $dia . '-' . $mes . '-' . $ano;

            $sql_IMOT = "WITH MESES AS (
                             SELECT
                                  to_char ( ADD_MONTHS( to_date('$fecha_IMOT','DD-MM-YYYY'), -( level-1 )) , 'YYYY' ) ||
                                  to_char ( ADD_MONTHS( to_date('$fecha_IMOT','DD-MM-YYYY'), -( level-1 )) , 'mm' ) FECHA,
                                  to_char ( ADD_MONTHS( to_date('$fecha_IMOT','DD-MM-YYYY'), -( level-1 )) , 'MON YYYY','nls_date_language=spanish' ) FECHA_FORMATO
                             FROM dual
                             CONNECT BY level <= 12
                             )
                             SELECT M.FECHA,
                                M.FECHA_FORMATO ,
                                RCP.PERIODO_RSH ,
                                RCP.RMIN_Y_LAB ,
                                RCP.RMAX_Y_LAB ,
                                RCP.FUENTE_LAB2 ,
                                RCP.RMIN_Y_PEN ,
                                RCP.RMAX_Y_PEN ,
                                RCP.FUENTE_PEN ,
                                RCP.RMIN_Y_K ,
                                RCP.RMAX_Y_K ,
                                RCP.FUENTE_K,
                                RCP.Y_LAB ,
                                RCP.Y_PEN ,
                                RCP.Y_K,
                                RCP.Y_LAB_HOGAR,
                                RCP.Y_PEN_HOGAR,
                                RCP.Y_K_HOGAR,
                                RCP.TRAMO_CSE,
                                FUNLAB.FUE_DESCRIPCION AS LAB,
                                FUNPEN.FUE_DESCRIPCION AS PEN,
                                FUNCAP.FUE_DESCRIPCION AS CAP
                             FROM  MESES M
                             LEFT JOIN (SELECT
                               RCP.PERIODO_RSH ,
                               RCP.RMIN_Y_LAB ,
                               RCP.RMAX_Y_LAB ,
                               RCP.FUENTE_LAB2 ,
                               RCP.RMIN_Y_PEN ,
                               RCP.RMAX_Y_PEN ,
                               RCP.FUENTE_PEN ,
                               RCP.RMIN_Y_K ,
                               RCP.RMAX_Y_K ,
                               RCP.FUENTE_K,
                               RCP.Y_LAB ,
                               RCP.Y_PEN ,
                               RCP.Y_K,
                               RCP.Y_LAB_HOGAR,
                               RCP.Y_PEN_HOGAR,
                               RCP.Y_K_HOGAR,
                               RCP.TRAMO_CSE
                           FROM  VW_TBL_PLATAFORMA_CSE_VIG RCP
                           WHERE RCP.RUN = $titular) RCP ON ( RCP.PERIODO_RSH = M.FECHA)
                             LEFT JOIN VW_FUENTES FUNLAB ON ( RCP.FUENTE_LAB2 = FUNLAB.FUE_ID )
                             LEFT JOIN VW_FUENTES_P FUNPEN ON ( RCP.FUENTE_PEN = FUNPEN.FUE_ID )
                             LEFT JOIN VW_FUENTES_K FUNCAP ON ( RCP.FUENTE_K = FUNCAP.FUE_ID )
                             ORDER BY M.FECHA ASC";

                $ingresoMonetariosObservados = DB::select($sql_IMOT);
                $data['ingresosObservados'] = $ingresoMonetariosObservados;
                $data['ocultarFilaIngreso'] = self::validaOcultarColumna($ingresoMonetariosObservados,'Ingresos');

            if(isset($data['baseGeneralPlantilla']['folioVigente']) && !is_null($data['baseGeneralPlantilla']['folioVigente']) ) {
                $sql_Salud = "SELECT
                                    A.RUN_CARGA
                                    ,A.RUN_COTIZANTE
                                    ,A.TIPO_BENEF
                                    ,A.ETIQUETA_CÓDIGO_ISAPRE
                                    ,A.RMIN_V19
                                    ,A.RMAX_V19
                                    ,A.PERCENTIL_V19
                                    ,A.DISAPRE_IND_ALTO_VALOR
                                    ,A.DISAPRE_IND_MUY_ALTO_VALOR
                                    ,A.MES_DEV
                                    ,A.VERSION_CSE
                                    ,A.FOLIO_HOGAR
                                    ,TO_CHAR(TO_DATE(A.MES_DEV_STR,'YYYYMM'),'MONTHYYYY','NLS_DATE_LANGUAGE = SPANISH') AS FECHA_ETIQUETA

                                FROM BASE_RT_BASE_COTIZANTES_CARGAS A
                                    WHERE
                                        A.FOLIO_HOGAR = " . $data['baseGeneralPlantilla']['folioVigente'] . "
                                        AND A.FECHA_CSE = $peridoBusqueda
                                        AND (A.DISAPRE_IND_ALTO_VALOR = 1 OR A.DISAPRE_IND_MUY_ALTO_VALOR = 1)
                                        AND ROWNUM <= " . $_ENV['MAX_REG_SALUD'];

                $salud = DB::select($sql_Salud);
                $data['salud'] = count($salud) > 0 ? $salud : null;
            }else{
                $data['salud'] = null;
            }
            if(isset($data['baseGeneralPlantilla']['folioVigente']) && !is_null($data['baseGeneralPlantilla']['folioVigente']) ) {
                $sql_Educacion = "SELECT
                                        A.RUN
                                        ,A.DV
                                        ,A.RBD
                                        ,A.NOM_RBD_MAT
                                        ,A.COD_COM_RBD_MAT
                                        ,A.AGNO
                                        ,A.DPP_IND
                                        ,A.MERGE_MATDIR_CSE
                                        ,A.FECHA_CSE
                                        ,B.COM_DESCRIPCION
                                        ,A.FOLIO_HOGAR
                                    FROM
                                        BASE_RT_DIRECTORIO_MATRICULA A
                                    LEFT JOIN  COMUNAS_INE B ON(A.COD_COM_RBD_MAT = B.COM_ID )
                                    WHERE
                                        A.FOLIO_HOGAR = " . $data['baseGeneralPlantilla']['folioVigente'] . "
                                        AND A.FECHA_CSE = $peridoBusqueda
                                        AND A.DPP_IND = 1
                                        AND ROWNUM <= " . $_ENV['MAX_REG_EDUCACION'];

                $educacion = DB::select($sql_Educacion);
                $data['educacion'] = count($educacion) > 0 ? $educacion : null;
            }else{
                $data['educacion'] = null;
            }

            if(isset($data['baseGeneralPlantilla']['folioVigente']) && !is_null($data['baseGeneralPlantilla']['folioVigente']) ) {
                $sql_BienesRaices = "SELECT
                                      RUN
                                      ,DV
                                      ,ROL
                                      ,MONTO_AV_MIN
                                      ,MONTO_AV_MAX
                                      ,MONTO_AV_COM_MIN
                                      ,MONTO_AV_COM_MAX
                                      ,COMUNA_TEXTO
                                      ,ANO
                                      ,SEMESTRE_TEXTO
                                      ,SUMA_MAX
                                      ,SUMA_MIN
                                      ,PERCENTIL
                                      ,FECHA_CSE
                                      ,DPROPS_IND_ALTO_VALOR
                                      ,DPROPS_IND_MUY_ALTO_VALOR
                                      ,NO_MOSTRAR
                                      ,FOLIO_HOGAR
                                    FROM
                                        BASE_RT_BIENES_RAICES
                                    WHERE
                                        FOLIO_HOGAR = " . $data['baseGeneralPlantilla']['folioVigente'] . "
                                        AND  FECHA_CSE = $peridoBusqueda
                                        AND ( DPROPS_IND_ALTO_VALOR = 1 OR DPROPS_IND_MUY_ALTO_VALOR = 1 )
                                        AND NO_MOSTRAR IS NULL
                                        AND ROWNUM <= " . $_ENV['MAX_REG_BIENES'];

                $bienesRaices = DB::select($sql_BienesRaices);
                $data['bienesRaices'] = count($bienesRaices) > 0 ? $bienesRaices : null;
            }else{
                $data['bienesRaices'] = null;
            }

            if(isset($data['baseGeneralPlantilla']['folioVigente']) && !is_null($data['baseGeneralPlantilla']['folioVigente']) ) {
                $sql_Vehiculo = "SELECT
                                    RUN
                                    ,DV
                                    ,PATENTE
                                    ,MARCA
                                    ,MODELO
                                    ,TIPO_PATENTE_TEXTO
                                    ,SUMA_MIN
                                    ,SUMA_MAX
                                    ,PERCENTIL
                                    ,MONTO_TAS_MIN
                                    ,MONTO_TAS_MAX
                                    ,DVEHICULOS_IND_ALTO_VALOR
                                    ,DVEHICULOS_IND_MUY_ALTO_VALOR
                                    ,AGNO_SII
                                    ,NO_MOSTRAR
                                    ,FECHA_CSE
                                    ,FOLIO_HOGAR
                                FROM BASE_RT_SRCEI_VEHICULOS
                                    WHERE FOLIO_HOGAR = " . $data['baseGeneralPlantilla']['folioVigente'] . "
                                        AND FECHA_CSE = $peridoBusqueda
                                        AND ( DVEHICULOS_IND_ALTO_VALOR = 1 OR DVEHICULOS_IND_MUY_ALTO_VALOR = 1 )
                                        AND NO_MOSTRAR IS NULL
                                        AND ROWNUM <= " . $_ENV['MAX_REG_VEHICULO'];

                $vehiculo = DB::select($sql_Vehiculo);
                $data['vehiculo'] = count($vehiculo) > 0 ? $vehiculo : null;
            }else{
                $data['vehiculo'] = null;
            }

            if(isset($data['baseGeneralPlantilla']['folioVigente']) && !is_null($data['baseGeneralPlantilla']['folioVigente']) ) {
                $sql_PensionAlimento = "SELECT
                                             A.RUT_CAUSANTE
                                             ,A.RUT_RECEPTOR
                                             ,A.RUT_PAGADOR
                                             ,DECODE(A.RUT_PAGADOR,$titular,'Paga','Recibe') AS TIPO
                                             ,A.RMIN_MONTO_BRUTO_DECLARADO
                                             ,A.RMAX_MONTO_BRUTO_DECLARADO
                                             ,TO_DATE(A.FECHA_APROBACION_SOLICITUD ,'ddMONYYYY HH24:mi:ss','NLS_DATE_LANGUAGE = AMERICAN') AS FECHA_APROBACION_SOLICITUD
                                             ,A.FECHA_CSE
     			                             ,TO_CHAR(TO_DATE(A.FECHA_CSE,'YYYYMM'),'MONTHYYYY','NLS_DATE_LANGUAGE = SPANISH') AS FECHA_ETIQUETA
                                             ,A.VERSION_CSE
                                             ,A.FOLIO_HOGAR_PAGADOR
                                             ,A.FOLIO_HOGAR_RECEPTOR
                                             ,B.PER_DV
                                             ,B.PER_NOMBRES
                                             ,B.PER_APELLIDOPATERNO
                                             ,B.PER_APELLIDOMATERNO
                                        FROM
                                            BASE_RT_PENSION_ALIMENTO A
                                            JOIN VW_PERSONAS B ON (B.PER_RUN = A.RUT_CAUSANTE)
                                                WHERE (A.FOLIO_HOGAR_PAGADOR = " . $data['baseGeneralPlantilla']['folioVigente'] . " OR A.FOLIO_HOGAR_RECEPTOR = " . $data['baseGeneralPlantilla']['folioVigente'] . ")
                                                    AND A.FECHA_CSE = $peridoBusqueda
                                                    AND ROWNUM <= " . $_ENV['MAX_REG_PENSION'];

                $pensionAlimento = DB::select($sql_PensionAlimento);
                $data['pensioAlimentos'] = count($pensionAlimento) > 0 ? $pensionAlimento : null;
            }else{
                $data['pensioAlimentos'] = null;
            }

            if(isset($data['baseGeneralPlantilla']['folioVigente']) && !is_null($data['baseGeneralPlantilla']['folioVigente']) ) {
                $sql_pmFueraHogar = "SELECT
                                        A.RUN
                                        ,A.RUN_DV
                                        ,A.RUN_PADRE_LATENTE
                                        ,A.COMUNA_PA
                                        ,A.FOLIO_HOGAR_1
                                        ,A.FOLIO_HOGAR_2
                                        ,A.RUN_PADRE_AUSENTE1
                                        ,A.RUN_PADRE_AUSENTE1_DV
                                        ,A.RUN_PADRE_AUSENTE2
                                        ,A.RUN_PADRE_AUSENTE2_DV
                                        ,A.DPAUSENTE_IND_ALTOA
                                        ,A.DPAUSENTE_IND_ALTOB
                                        ,A.DPAUSENTE_IND_MUYALTO
                                        ,A.PERCENTIL_PA
                                        ,A.FECHA_CSE
     			                        ,TO_CHAR(TO_DATE(A.FECHA_CSE,'YYYYMM'),'MONTHYYYY','NLS_DATE_LANGUAGE = SPANISH') AS FECHA_ETIQUETA
                                        ,A.ETIQUETA_COMUNA_PA
                                        ,A.VERSION_CSE
                                        ,A.FOLIO_HOGAR
                                        FROM (
                                            SELECT
                                                CSE.RUN
                                                ,CSE.PERIODO_RSH
                                                ,CSE.DPAUSENTE_ALTOA
                                                ,CSE.DPAUSENTE_ALTOB
                                                ,CSE.DPAUSENTE_MUYALTO
                                            FROM VW_TBL_PLATAFORMA_CSE_VIG CSE
                                                WHERE ( CSE.DPAUSENTE_ALTOA = 1 OR CSE.DPAUSENTE_ALTOB = 1 OR CSE.DPAUSENTE_MUYALTO = 1 )
                                                    AND CSE.PERIODO_RSH = $peridoBusqueda
                                            ) B
                                            JOIN BASE_RT_PADRE_AUSENTE A ON ( A.RUN = B.RUN )
                                            WHERE A.FECHA_CSE = $peridoBusqueda
                                                AND A.FOLIO_HOGAR =" . $data['baseGeneralPlantilla']['folioVigente'] . "
                                                AND ( A.DPAUSENTE_IND_ALTOA = 1 OR A.DPAUSENTE_IND_ALTOB = 1  OR A.DPAUSENTE_IND_MUYALTO = 1 )
                                                AND ROWNUM <= " . $_ENV['MAX_REG_PADREMADRE'];

                $pmFueraHogar = DB::select($sql_pmFueraHogar);
                $data['pmFueraHogar'] = count($pmFueraHogar) > 0 ? $pmFueraHogar : null;
            }else{
                $data['pmFueraHogar'] = null;
            }


                if(isset($data['baseGeneralPlantilla']['folioVigente']) && !is_null($data['baseGeneralPlantilla']['folioVigente']) ) {
                    $sql_ComposicionHogar = "SELECT
                                                A.RUN_FALSO
                                                ,A.PER_FIC_PARENTESCO
                                                ,A.EDAD
                                                ,A.FECHA_FOLIO_ACTUAL
                                                ,A.FOLIO_FALSO_ANTERIOR
                                                ,A.DEPENDENCIA
                                                ,A.ETIQUETA_DEPENDENCIA
                                                ,TO_CHAR(A.COEFICIENTE,'0.00') as COEFIC
                                                ,A.ETIQUETA_FUENTE_DEPENDENCIA
                                                ,A.INDICE_DE_NECESIDADES
                                                ,A.CONTADOR
                                                ,A.FOLIO_FALSO
                                                ,A.FECHA_CSE
                                                ,TO_CHAR(DECODE(A.CONTADOR,1,ADD_MONTHS(TO_DATE(A.FECHA_CSE,'YYYYMM'),-1),2,ADD_MONTHS(TO_DATE(A.FECHA_CSE,'YYYYMM'),-2), TO_DATE(A.FECHA_CSE,'YYYYMM')),'MONTHYYYY','NLS_DATE_LANGUAGE = SPANISH') AS FECHA_ETIQUETA
                                                ,B.PER_DV
                                                ,B.PER_NOMBRES
                                                ,B.PER_APELLIDOMATERNO
                                                ,B.PER_APELLIDOPATERNO
                                              FROM BASE_RT_COMPOSICION_HOGAR A
                                                JOIN VW_PERSONAS B ON (A.RUN_FALSO = B.PER_RUN)
                                                  WHERE A.FOLIO_FALSO = " . $data['baseGeneralPlantilla']['folioVigente'] . "
                                                    AND A.FECHA_CSE = $peridoBusqueda";

                    $composicionHogar = DB::select($sql_ComposicionHogar);
                    $data['composicionHogar'] = count($composicionHogar) > 0 ? $composicionHogar : null;
                    $data['ocultarFilaHogar'] = self::validaOcultarColumna($composicionHogar,'Hogar');

                }else{
                    $data['composicionHogar'] = null;
                }

                $sql_Solicitud ="SELECT
                                    A.RUN_CIUDADANO
                                    ,A.NUEVOFOLIO
                                    ,A.ID_SOLICITUD
                                    ,A.ID_ESTADO
                                    ,A.ID_SUB_ESTADO
                                    ,A.COM_ID
                                    ,A.FECHA_CREACION
                                    ,A.FECHA_MODIFICACION
                                    ,A.ID_TIPO_SOLICITUD
                                    ,B.TIPO_SOLICITUD
                                    ,C.NOMBRE_ESTADO
                                    ,D.NOMBRE_SUB_ESTADO
                                    ,E.COM_DESCRIPCION
                                FROM
                                   VW_AB_SOLICITUD_36 A
                                   LEFT JOIN VW_AB_TIPO_SOLICITUD B ON ( A.ID_TIPO_SOLICITUD = B.ID_TIPO_SOLICITUD )
                                   LEFT JOIN VW_AB_ESTADO_SOLICITUD C ON ( A.ID_ESTADO = C.ID_ESTADO )
                                   LEFT JOIN VW_AB_SUB_ESTADO_SOLICITUD D ON ( A.ID_SUB_ESTADO = D.ID_SUB_ESTADO )
                                   LEFT JOIN COMUNAS_INE E ON ( A.COM_ID = E.COM_ID )
                                WHERE RUN_CIUDADANO = $titular";

                $solicitud = DB::select($sql_Solicitud);
                $data['solicitudes'] = count($solicitud) > 0 ? $solicitud : null;
                //Rol del Reporte
                $array = array();
                $rol =  Session::get("heskUserRol");
                if(! is_null($rol)){
                    foreach ( $rol  as $item){
                        array_push($array, $item->id_rol);
                    }
                    $data['rol'] = $array;
                }else{
                    $data['rol'] = null;
                }

        } catch (Exception $ex) {
            $data = null;
            log::error(
                "Error al obtener datos para reporte.",
                [
                    "mensaje" => $ex->getMessage(),
                    "trace" => $ex->getTraceAsString(),
                    "titular" => $titular,
                    "solicitante" => $solicitante
                ]
            );
        }
        return $data;
    }

    public function emailGenerado(Request $request, $idSolicitud)
    {
        $idSolicitud = Enigma::decrypt($idSolicitud);
        $data = ['data' => 'Error al procesar envio de correo'];
        $statusCode = 400;
        if (is_numeric($idSolicitud)) {
            $updateSolicitud = Consultas::where('id_consulta', $idSolicitud)->first();
            if (is_object($updateSolicitud)) {
                if (!is_null($updateSolicitud->email)) {
                    //se marca 0 enviado a cola
                    $details['id'] = $updateSolicitud->id_consulta;
                    $updateSolicitud->correo_enviado = Constante::CORREO_ENVIADO_REPROCESO_ENCOLAMIENTO;
                    $updateSolicitud->save();
                    dispatch(new SendEmailJob($details));
                    $data['data'] = "Se proceso con exito el reenvio del correo";
                    $statusCode = 200;
                } else {
                    //marcamos el campo porque no se envio correo
                    $updateSolicitud->correo_enviado = Constante::NO_POSEE_CORREO_NO_ENVIADO;
                    $updateSolicitud->save();
                    log::info(
                        'No se puede reenviar correo porque no existe email asociado.',
                        [
                            'idSolicitud' => $idSolicitud
                        ]
                    );
                }
            } else {
                log::error(
                    'Error no se encontro Solicitud.',
                    [
                        'idSolicitud' => $idSolicitud
                    ]);
            }
        } else {
            log::error(
                'Error al reprocesar envio de correo, solicitud no encontrada.',
                [
                    'idSolicitud' => $idSolicitud
                ]);
        }
        return response::json($data, $statusCode);
    }
    public function cargaInicialReporte($titular,$solicitante){

        $buscaMaximoPeridoCargado = "SELECT max(PERIODO_RSH) AS PERIODO FROM  RSH_CONSULTAS.vw_tbl_plataforma_cse_vig";
        $csePeridoBusqueda = DB::select($buscaMaximoPeridoCargado);

        $data['periodoBusqueda'] = [
            'periodo'=> $csePeridoBusqueda[0]->periodo,
        ];
        $periodoBusq = $data['periodoBusqueda']['periodo'];

        $csePlataforma_SQL = "SELECT
                                FOLIO ,
                                TRAMO_CSE ,
                                TRAMO_CSE_SIN_SUAV ,
                                NT_C
                              FROM
                                RSH_CONSULTAS.VW_TBL_PLATAFORMA_CSE_VIG
                              WHERE RUN = $titular
                                AND PERIODO_RSH = $periodoBusq";

        $csePlataforma = DB::select($csePlataforma_SQL);

        if(count($csePlataforma))
        {
            $folioActual = $csePlataforma[0]->folio;

            $data['composicionHogarCSE'] = [
                            'suavizacion'=> $csePlataforma[0]->tramo_cse,
                            'suavizacion1'=> $csePlataforma[0]->tramo_cse_sin_suav,
                            'indice' => $csePlataforma[0]->nt_c
                            ];
        }

        $csePersonas_SQL = "SELECT
                                PER_RUN ,
                                PER_DV ,
                                PER_NOMBRES ,
                                PER_APELLIDOPATERNO ,
                                PER_APELLIDOMATERNO
                              FROM
                                RSH_CONSULTAS.VW_PERSONAS
                              WHERE PER_RUN = $titular";

        $personaExport = DB::select($csePersonas_SQL);
        /*$personaExport = DB::connection('oracle_export')
            ->table('personas')
            ->where('per_run', $titular)
            ->first();*/
        if(count($personaExport)) {
            $data['personalTitular'] = [
                'runTitular' => $personaExport[0]->per_run,
                'dvTitular' => $personaExport[0]->per_dv,
                'nombreTitular' => $personaExport[0]->per_nombres,
                'apellidoPTitular' => $personaExport[0]->per_apellidopaterno,
                'apellidoMTitular' => $personaExport[0]->per_apellidomaterno
            ];
        }

        $data['baseGeneralPlantilla'] = [
            'runSolicitante' => $solicitante,
            'dvSolicitante' => Utiles::dv($solicitante),
            'nombreSolicitante' => Session::get("heskUserNombre"),
            'folioVigente' => isset($folioActual) ? $folioActual : null,
            'titulo_item1' => Constante::RG_TITULO_ITEM1,
            'subtituloItem1' => Constante::RG_SUBTITULO_ITEM1,
            'titulo_item2' => Constante::RG_TITULO_ITEM2,
            'subtituloItem2' => Constante::RG_SUBTITULO_ITEM2,
            'titulo_item3' => Constante::RG_TITULO_ITEM3,
            'subtituloItem3' => Constante::RG_SUBTITULO_ITEM3,
            'titulo_item4' => Constante::RG_TITULO_ITEM4,
            'subtituloItem4' => Constante::RG_SUBTITULO_ITEM4,
            'titulo_item5' => Constante::RG_TITULO_ITEM5,
            'subtituloItem5' => Constante::RG_SUBTITULO_ITEM5,
            'titulo_item6' => Constante::RG_TITULO_ITEM6,
            'subtituloItem6' => Constante::RG_SUBTITULO_ITEM6
        ];

        return $data;
    }
    //
    static public function validaOcultarColumna($data,$tipo)
    {

        if ($tipo == 'Ingresos') {

            $banderaTabla = true;
            $banderaTrb = false;
            $banderaIngTrb = true;
            $banderaPen = false;
            $banderaIngPen = true;
            $banderaCap = false;
            $banderaIngCap = true;

            $inicialTrb = trim( (is_null( $data[0]->lab)) ? 'vacio' : $data[0]->lab);
            $inicialPen = trim( (is_null( $data[0]->pen)) ? 'vacio' : $data[0]->pen);
            $inicialCap = trim((is_null( $data[0]->cap)) ? 'vacio' : $data[0]->cap);

            $search = "Dep.";
            $replace = "dependiente ";
            $resultFunTrb = str_ireplace($search, $replace, $inicialTrb);
            $resultFunPen = str_ireplace($search, $replace, $inicialPen);
            $resultFunCap = str_ireplace($search, $replace, $inicialCap);
            $search2 = "Indep.";
            $replace2 = "independiente ";
            $resultFunTrb2 = str_ireplace($search2, $replace2, $resultFunTrb);
            $resultFunPen2 = str_ireplace($search2, $replace2, $resultFunPen);
            $resultFunCap2 = str_ireplace($search2, $replace2, $resultFunCap);


            foreach ($data as $items) {

                if ($inicialTrb != (is_null( trim($items->lab)) ? 'vacio' : trim($items->lab)) )  {
                    $banderaTrb = true;
                }
                if ($inicialPen != (is_null( trim($items->pen)) ? 'vacio' : trim($items->pen)) ) {
                    $banderaPen = true;
                }
                if ($inicialCap != (is_null( trim($items->cap)) ? 'vacio' : trim($items->cap)) ) {
                    $banderaCap = true;
                }
            }

            if( ! $banderaTrb ){
               ($inicialTrb == 'Sin ingresos') ? $banderaIngTrb = false : $banderaIngTrb = true;
            }
            if( ! $banderaPen ){
               ($inicialPen == 'Sin Pensión') ? $banderaIngPen = false : $banderaIngPen = true;
            }
            if( ! $banderaCap ){
               ($inicialCap == 'Sin ingresos de capital') ? $banderaIngCap = false : $banderaIngCap = true;
            }

            if( $banderaIngTrb ){
                $txtTrb = "La fuente considerada para todos los meses señalados en ingreso de trabajo es " . $resultFunTrb2;
            }else{
                $txtTrb = "Con respecto a ingreso de trabajo del titular, no se observan ingresos en ninguno de los meses señalados";
            }

            if( $banderaIngPen ) {
                $txtPen = "La fuente considerada para todos los meses señalados en ingreso de pensiones es " . $resultFunPen2;
            }else{
                $txtPen = "Con respecto a ingreso de pensiones del titular, no se observan ingresos en ninguno de los meses señalados";
            }
            if( $banderaIngCap ) {
                $txtCap = "La fuente considerada para todos los meses señalados en ingresos del capital es " . $resultFunCap2;
            }else{
                $txtCap = "Con respecto a ingreso de capital del titular, no se observan ingresos en ninguno de los meses señalados";
            }

            //oculta toda la tabla
           if( (!$banderaTrb && !$banderaIngTrb && !$banderaPen && !$banderaIngPen && !$banderaCap  && !$banderaIngCap) ){
               $banderaTabla = false;
           }
            $data['ocultaIngresos'] = [
                'fuenteTrabajo' => $banderaTrb,
                'fuenteIngTrabajo' => $banderaIngTrb,
                'fuenteTrabajoTxt' => ($banderaTrb) ? '' : $txtTrb ,
                'fuentePension' => $banderaPen,
                'fuenteIngPension' => $banderaIngPen,
                'fuentePensionTxt' => ($banderaPen) ? '' : $txtPen ,
                'fuenteCapital' => $banderaCap,
                'fuenteIngCapital' => $banderaIngCap,
                'fuenteCapitalTxt' => ($banderaCap) ? '' : $txtCap,
                'ocultaTodotabla' => $banderaTabla
            ];

            return $data['ocultaIngresos'];
        }

        if ($tipo == 'Hogar') {

            $banderaDiscp = true;
            $banderaFnte = true;
            $txtDiscp="";
            $txtFnte="";
            $inicialDiscp = (is_null( $data[0]->etiqueta_dependencia)) ? 'vacio' : $data[0]->etiqueta_dependencia;
            $inicialFnte = (is_null( $data[0]->etiqueta_fuente_dependencia)) ? 'vacio' : $data[0]->etiqueta_fuente_dependencia;

            foreach ($data as $items) {
                if ($inicialDiscp != (is_null( $items->etiqueta_dependencia) ? 'vacio' : $items->etiqueta_dependencia) )  {
                    $banderaDiscp = false;
                }
                if ($inicialFnte != (is_null( $items->etiqueta_fuente_dependencia) ? 'vacio' : $items->etiqueta_fuente_dependencia) )  {
                    $banderaFnte = false;
                }
            }

            if( $banderaDiscp ){
                $txtDiscp = "La Dependencia/Discapacidad presente es " . $inicialDiscp;
            }
            if( $banderaFnte){
               $txtFnte = "La fuente presente es " . $inicialFnte;
           }

            $data['ocultaHogar'] = [
                'discapacidad' => $banderaDiscp,
                'discapacidadTxt' => $txtDiscp,
                'fuente' => $banderaFnte,
                'fuenteTxt' => $txtFnte
            ];

            return $data['ocultaHogar'];
        }
    }

    public function envioCorreoPrueba($id){
        $updateSolicitud = Consultas::where('id_consulta', $id)->first();
        $reportes = Reportes::all()->pluck('nombre_reporte', 'id_reporte')->toArray();
        if (is_object($updateSolicitud)) {
            $data["email"] = $updateSolicitud->email;
            $data["id"] = $updateSolicitud->id_consulta;
            $data["title"] = "Notificacion de Solicitud de Reporte";
            $data["tipo"] = $updateSolicitud->reporte;
            $data["fecha"] = $updateSolicitud->acceso;
            $data["reportes"] = $reportes;
            $filename = storage_path() . '/Reportes/' . $updateSolicitud->nombre_archivo;
            $files = [$filename];

            Mail::send('transparencia.email.solicitud', $data, function ($message) use ($data, $files) {
                $message->to($data["email"], $data["email"])
                    ->subject($data["title"]);
                foreach ($files as $file) {
                    $message->attach($file);
                }
            });
            return "ok";
        }

    }
}
