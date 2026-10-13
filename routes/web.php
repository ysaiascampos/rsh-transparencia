<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function (Request $request) {
    return view('cuenta.index');
})->name('home');

Route::get('/salir', 'AuthController@logOut');

Route::get('/login', function () {
    return view('cuenta.ingresar');
})->name('login');

//retorno login SSO
Route::get('postlogin', 'AuthController@loginsso')
    ->name('postlogin');
Route::post('postlogin', 'AuthController@loginsso');

// login
Route::post('ingresar', 'AuthController@postLogin');

//Rutas que solo se permiten al estar autenticado
Route::middleware('auth')->group(function () {
    Route::get('/escritorio', 'ConsultasEscritorioController@show')
        ->name('consultas_escritorio');

    Route::get('/consulta_run', 'SolicitudController@show')
        ->name('consulta_run');

    Route::get('/seguimiento_interno', 'SeguimientoController@SeguimientoInterno')
        ->name('seguimiento_interno');

    // Mantenedores de usuarios
    Route::get('/mantenedor_usuario', 'MantenedorUsuariosController@show')
        ->name('mantenedor_usuario');
    Route::post('/buscarUsuario', 'MantenedorUsuariosController@buscarUsuario');
    Route::post('/detalleUsuario', 'MantenedorUsuariosController@detalleUsuario');
    Route::post('/actualizaUsuario', 'MantenedorUsuariosController@actualizaUsuario');

    //descargar excel de folios a Resultados
    Route::post('/descargaFolios/excelResultados', 'ConsultasEscritorioController@descargaResultadosEscritorio')
        ->name('DescargaResultadosEscritorio');

    //descargar documentos en base a idSolicitud
    Route::get('/descargaAdjuntos/{idSol}/{idDoc?}', 'SolicitudController@descargarAdjuntos')
        ->name('/descargaAdjuntos');

    Route::get('/descargaGenerado/{idSol}', 'SolicitudController@descargarGenerado')
        ->name('/descargaGenerado');
    Route::get('/emailGenerado/{idSol}', 'SolicitudController@emailGenerado')
        ->name('/emailGenerado');

    Route::post('/guardar_solicitud', 'SolicitudController@store');
    Route::get('/ver_solicitud/{idConsulta}', 'SolicitudController@verConsulta');

    Route::get('/solicitud_descarga', function () {
        return view('transparencia.menu.solicitud_exitosa');
    })->name('solicitud_descarga');

    Route::post('/consultar_rut', 'ConsultasEscritorioController@consultarRut');

    //Rutas para segunda fase.
//    Route::get('/seguimiento_externo', function () {
//        return view('transparencia.menu.seguimiento_consulta_externo');
//    })->name('seguimiento_externo');
//    Route::get('/mantenedor_perfil', 'MantenedorPerfilesController@show')
//        ->name('mantenedor_perfil');
//    Route::get('/consulta_lote', function () {
//        return view('transparencia.menu.consulta_run_lote');
//    })->name('consulta_lote');

    //Escritorio consultas
    //Route::get('/escritorio', 'ConsultasEscritorioController@show')
    //    ->name('consultas_escritorio');

    /*
    Route::get('/consulta_run',function () {
        return view('transparencia.menu.consulta_run');
    })->name('consulta_run');
    */

});
//prueba de graficos
Route::get('/grafico', 'SolicitudController@graphs');
Route::get('/graficoPDF', 'SolicitudController@graphPdf');
Route::get('/envioCorreoPrueba/{idSol}', 'SolicitudController@envioCorreoPrueba');


$acceso_debug = env('ambienteDebug', false);
if ($acceso_debug) {
    Route::prefix('/desarrollo')
        ->group(function () {
            //Ruta para ver logs
            Route::get('logs/' . env('CLAVE_UNICA_TOKEN_FALSO','94355a1824e3492298ccfbc7c96f04e4'), '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
        });
}
