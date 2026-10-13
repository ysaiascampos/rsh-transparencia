@extends('transparencia.main')
@section('content')<!-- Section Title -->
    <section class="banner-home2">
        <div class="container">
            <div class="row no-gutters justify-content-between align-items-center">
                <h2 class="text-white mb-0">Seguimiento de consultas</h2>
                <small class="text-white">
                    <time>{{(new DateTime("now", new DateTimeZone('America/Santiago')))->format('H:m d/m/Y')}}</time>
                </small>
            </div>
        </div>
    </section>
<!-- Main Section -->
<section class="main">
    <div class="container">
        <div class="card my-3 shadow-sm">
            <div class="card-body">
                @include('transparencia.include.layout.navigation')

                <div class="row p-3">
                    <div class="col-12">
                        <h3>Registro N°: {{$id}} </h3>
                    </div>
                    <div class="col-12">
                        <h6>Datos Personales: </h6>
                        <div class="card">
                            <div class="card-body">
                                <div class="card-text d-flex flex-column">
                                    <div class="row">
                                        <strong class="col-7 mb-2">RUN: </strong>
                                        <span>{{\App\Http\Controllers\Utiles::ponePuntosNumero($dataReporte[0]->run_solicitante)}}
                                            -
                                            {{\App\Http\Controllers\Utiles::dv($dataReporte[0]->run_solicitante)}}</span>
                                        <strong class="col-7 mb-2">Nombre: </strong>
                                        <span>{{$dataReporte[0]->nombre_solicitante}}</span>
                                        <strong class="col-7 mb-2">Cargo: </strong>
                                        <span>{{$dataReporte[0]->nombre_cargo}}</span>
                                        <strong class="col-7 mb-2">Perfil: </strong>
                                        <span>{{$dataReporte[0]->nombre_perfil}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <h6>Datos de la Solicitud: </h6>
                        <div class="card">
                            <div class="card-body">
                                <div class="card-text d-flex flex-column">
                                    <div class="row">
                                        <strong class="col-7 mb-2">RUN: </strong>
                                        <span>{{\App\Http\Controllers\Utiles::ponePuntosNumero($personaExp[0]->per_run)}}
                                            -
                                            {{$personaExp[0]->per_dv}}</span>
                                        <strong class="col-7 mb-2">Nombre: </strong>
                                        <span>{{$personaExp[0]->per_nombres}} {{$personaExp[0]->per_apellidopaterno}} {{$personaExp[0]->per_apellidomaterno}}</span>
                                        <strong class="col-7 mb-2">Nombre Reporte: </strong>
                                        <span>{{$dataReporte[0]->nombre_reporte}}</span>
                                        <strong class="col-7 mb-2">Justificacion ó Razon: </strong>
                                        <span>11</span>
                                        <strong class="col-7 mb-2"></strong>
                                        <strong class="col-7 mb-2">Archivos Adjuntados:</strong>
                                        <div class="col-12 custom-list mb-2">
                                            <ul class="list-group list-group-flush bg-primary__light custom-list__column">
                                                @if(count($dataDocs) > 0)
                                                    @foreach( $dataDocs as $docs)
                                                    <li class="list-group-item px-0">
                                                        <i class="mdi mdi-paperclip">{{$docs->nombre_original}}</i>&nbsp
                                                        <a name="elemDescargar{{$docs->id_documento}}"
                                                           id="elemDescargar{{$docs->id_documento}}"
                                                           data-id="{{$docs->id_documento}}"
                                                           href="#"
                                                           onclick="getFile('{{\App\Classes\Enigma::encrypt($id)}}','{{$docs->id_documento}}','{{$docs->nombre_original}}')">
                                                            <i class="mdi mdi-file-download"></i></a>
                                                    </li>
                                                    @endforeach
                                                @else
                                                    <div style="text-align: center"><strong>No hay Archivo Adjuntos</strong></div>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 p-2">
                            <button id=""
                                    name=""
                                    type="button"
                                    class="btn btn-primary"
                                    onclick="window.location.href= '../seguimiento_interno';">
                                Cancelar
                            </button>
                            <button id="" name="" type="button" class="btn btn-success" onclick="getReporte('{{\App\Classes\Enigma::encrypt($id)}}','{{$dataReporte[0]->nombre_archivo}}')">
                                <i class="mdi mdi-file-download"></i>Descargar Reporte</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop

