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
                        <form id="frmFiltros" class="form-row">
                        <div class="card" style="min-width: 100%">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-md-4 d-flex mb-1">
                                                <i class="mdi mdi-magnify mr-1"></i>
                                                RUN
                                            </div>
                                            <div class="col-md-8">
                                                    <div class="form-row">
                                                        <div class="col-md-12 mb-2">
                                                            <label class="sr-only" for="run">RUN</label>
                                                            <input type="text"
                                                                   autofocus="autofocus"
                                                                   class="form-control"
                                                                   id="runConsulta"
                                                                   name="runConsulta"
                                                                   placeholder="12.345.678-9"
                                                                   autocomplete="off"
                                                                   value="{{$runConsulta ?? ''}}">
                                                            <small id="run-info"
                                                                   class="form-text text-muted">
                                                                Ingrese rut sin puntos y dígito verificador
                                                            </small>
                                                            <div class="valid-feedback"> Correcto
                                                            </div>
                                                            <!-- Cuando hay error <div class="invalid-feedback"> Run no pertenece al registro Social de Hogares </div>-->
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-md-2 d-flex mb-1">
                                                <i class="mdi mdi-magnify mr-1"></i>
                                                Fechas</div>
                                            <div class="col-md-10">
                                                <div class="row align-items-center mb-2">
                                                    <div class="col-md-6">
                                                        <label class="sr-only" for="fechainicio">
                                                            Fecha de inicio
                                                        </label>
                                                        <input type="date"
                                                               class="form-control mb-2 mb-md-0"
                                                               id="fechainicio"
                                                               name="fechainicio"
                                                               value="{{$fechai ?? ''}}"
                                                               min="2020-01-01"
                                                               max="2030-12-31">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="sr-only" for="fechafinal">
                                                            Fecha final
                                                        </label>
                                                        <input type="date"
                                                               class="form-control mb-2 mb-md-0"
                                                               id="fechafinal"
                                                               name="fechafinal"
                                                               value="{{$fechaf ?? ''}}"
                                                               min="2020-01-01"
                                                               max="2030-12-31">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3 mb-md-0">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-md-12 d-flex">
                                                <button id='btnBuscar'
{{--                                                        name='btnBuscar'--}}
                                                        type="button"
                                                        class="btn btn-primary mb-2 col-md-12 mx-1">Buscar</button>
                                            </div>
                                            <div class="col-md-12 d-flex">
                                                <button id='btnLimpiar'
                                                        type="button"
                                                        class="btn btn-secondary mb-2 col-md-12 mx-1">Limpiar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="col-12 p-3">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
{{--                            <li class="nav-item" role="presentation">--}}
{{--                                <a class="nav-link active" id="pills-run-tab" data-toggle="pill" href="#pills-run" role="tab"--}}
{{--                                   aria-controls="pills-run" aria-selected="true">Consultas por RUN</a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item" role="presentation">--}}
{{--                                <a class="nav-link" id="pills-lote-tab" data-toggle="pill" href="#pills-lote" role="tab"--}}
{{--                                   aria-controls="pills-lote" aria-selected="false">Consultas por lote</a>--}}
{{--                            </li>--}}
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <!--tabla por run-->
                            <div class="tab-pane fade show active" id="pills-run" role="tabpanel" aria-labelledby="pills-run-tab">
                                <div class="table-responsive">
                                    <table id="tblConsultas" class="table table-hover table-sm custom-big-table">
                                        <thead class="thead-light">
                                        <tr>
                                            <th scope="col">RUN</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Razón consulta</th>
                                            <th scope="col">Reporte</th>
                                            <th scope="col">Fecha solicitud</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if (is_object($lstConsultas))
                                            @foreach($lstConsultas as $registro)
                                            <tr>
                                                <td>{{\App\Http\Controllers\Utiles::ponePuntosNumero($registro->run_titular)}}
                                                    -
                                                    {{\App\Http\Controllers\Utiles::dv($registro->run_titular)}}</td>
                                                <td>{{$registro->nombre}}</td>
                                                <td>{{$registro->descripcion_razon}} {{$registro->texto_justificacion}}</td>
                                                <td>{{$registro->nombre_reporte}}</td>
                                                <td>{{$registro->acceso}}</td>
                                                <td>
                                                <div class="">
                                                    <div class="btn-group">
                                                        <button type="button"
                                                                class="btn btn-info btn-sm"
                                                                onclick="getFile('{{\App\Classes\Enigma::encrypt($registro->id_consulta)}}',
                                                                    '',
                                                                    '{{$registro->rut . "_" . date("Y-m-d") . ".zip"}}')">
                                                            <i class="mdi mdi-file-download"></i>
                                                            Justificación adjuntada</button>
                                                        &nbsp;
                                                        <button type="button" class="btn btn-success btn-sm"
                                                                onclick="getReporte('{{\App\Classes\Enigma::encrypt($registro->id_consulta)}}','{{$registro->nombre_archivo}}')">
                                                            <i class="mdi mdi-file-download"></i>
                                                            Descargar Reporte
                                                        </button>
                                                        &nbsp;
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                                onclick="verSolicitud('{{\App\Classes\Enigma::encrypt($registro->id_consulta)}}')">
                                                            <i class="mdi mdi-eye"></i>
                                                            Ver Consulta
                                                        </button>
                                                        &nbsp;
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                                onclick="emailSolicitud('{{\App\Classes\Enigma::encrypt($registro->id_consulta)}}')">
                                                            <i class="mdi mdi-rotate-right"></i>
                                                            <i class="mdi mdi-mail"></i>
                                                            Reenvio Correo
                                                        </button>
                                                    </div>
                                                </div>

                                            </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <!--tabla por lote-->
{{--                            <div class="tab-pane fade" id="pills-lote" role="tabpanel" aria-labelledby="pills-lote-tab">--}}
{{--                                <div class="table-responsive">--}}
{{--                                    <table class="table table-hover">--}}
{{--                                        <thead class="thead-light">--}}
{{--                                        <tr>--}}
{{--                                            <th scope="col">Nombre consulta</th>--}}
{{--                                            <th scope="col">Archivo</th>--}}
{{--                                            <th scope="col">Razón consulta</th>--}}
{{--                                            <th scope="col">Reporte</th>--}}
{{--                                            <th scope="col">Meses</th>--}}
{{--                                            <th scope="col">Fecha solicitud</th>--}}
{{--                                            <th scope="col">Estado</th>--}}
{{--                                            <th scope="col">Justificación</th>--}}
{{--                                            <th scope="col">Resumen</th>--}}
{{--                                        </tr>--}}
{{--                                        </thead>--}}
{{--                                        <tbody>--}}
{{--                                        <tr>--}}
{{--                                            <td>Consulta noviembre de 2019, comuna 1</td>--}}
{{--                                            <td>RUN_101202020.csv </td>--}}
{{--                                            <td>Respuesta plataforma de transparencia</td>--}}
{{--                                            <td>Reporte básico</td>--}}
{{--                                            <td>3 meses</td>--}}
{{--                                            <td>08-10-2020</td>--}}
{{--                                            <td>Enviado</td>--}}
{{--                                            <td><button type="button" class="btn btn-info btn-sm"><i class="mdi mdi-file-download"></i>--}}
{{--                                                    Justificación</button></td>--}}
{{--                                            <td>--}}
{{--                                                <div class="d-flex flex-column flex-lg-row">--}}
{{--                                                    <button type="button" class="btn btn-success btn-sm btn-table mr-lg-2">--}}
{{--                                                        <i class="mdi mdi-file-download"></i>--}}
{{--                                                        Reporte--}}
{{--                                                    </button>--}}
{{--                                                    <button type="button" class="btn btn-primary btn-sm btn-table">--}}
{{--                                                        <i class="mdi mdi-eye"></i>--}}
{{--                                                        Ver--}}
{{--                                                    </button>--}}
{{--                                                </div>--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Consulta noviembre de 2019, comuna 1</td>--}}
{{--                                            <td>RUN_101202020.csv </td>--}}
{{--                                            <td>Respuesta plataforma de transparencia</td>--}}
{{--                                            <td>Reporte básico</td>--}}
{{--                                            <td>3 meses</td>--}}
{{--                                            <td>08-10-2020</td>--}}
{{--                                            <td><button type="button" class="btn btn-info btn-sm"><i class="mdi mdi-file-download"></i>--}}
{{--                                                    Justificación</button></td>--}}
{{--                                            <td>Enviado</td>--}}
{{--                                            <td>--}}
{{--                                                <div class="d-flex flex-column flex-lg-row">--}}
{{--                                                    <button type="button" class="btn btn-success btn-sm btn-table mr-lg-2">--}}
{{--                                                        <i class="mdi mdi-file-download"></i>--}}
{{--                                                        Reporte--}}
{{--                                                    </button>--}}
{{--                                                    <button type="button" class="btn btn-primary btn-sm btn-table">--}}
{{--                                                        <i class="mdi mdi-eye"></i>--}}
{{--                                                        Ver--}}
{{--                                                    </button>--}}
{{--                                                </div>--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        </tbody>--}}
{{--                                    </table>--}}
{{--                                </div>--}}
{{--                                <div class="col-12 d-flex justify-content-center">--}}
{{--                                    <nav aria-label="Página de navegación">--}}
{{--                                        <ul class="pagination">--}}
{{--                                            <li class="page-item">--}}
{{--                                                <a class="page-link" href="#" aria-label="Antes">--}}
{{--                                                    <span aria-hidden="true">&laquo;</span>--}}
{{--                                                </a>--}}
{{--                                            </li>--}}
{{--                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>--}}
{{--                                            <li class="page-item"><a class="page-link" href="#">2</a></li>--}}
{{--                                            <li class="page-item"><a class="page-link" href="#">3</a></li>--}}
{{--                                            <li class="page-item">--}}
{{--                                                <a class="page-link" href="#" aria-label="Siguiente">--}}
{{--                                                    <span aria-hidden="true">&raquo;</span>--}}
{{--                                                </a>--}}
{{--                                            </li>--}}
{{--                                        </ul>--}}
{{--                                    </nav>--}}
{{--                                </div>--}}
{{--                            </div>--}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $("#nav-seguimiento").attr('class', 'nav-link active');
    $('#tblConsultas').dataTable({
        "processing": false,
        "responsive": true,
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50, 75, 100],
        "order": [[ 4, "desc" ]],
        'language': {
            "decimal": "",
            "emptyTable": "No hay datos para mostrar",
            "info": "Mostrando _START_ al _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 al 0 de 0 registros",
            "infoFiltered": "(filtrado de _MAX_ registros)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "No hay registros para esa busqueda",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Previo"
            },
            "aria": {
                "sortAscending": ": activar para ordenar columna de forma ascendente",
                "sortDescending": ": activar para ordenar columna de forma descendente"
            }
        }
    });

    function verSolicitud(id)
    {
        window.location.href = '{{asset('/ver_solicitud')}}/' + id;
    }

    $('#btnBuscar').click(function () {

        Swal.fire({
            title: "Espere por favor...",
            allowEscapeKey: false,
            allowOutsideClick: false,
            onOpen: () => {
                Swal.showLoading();
            }
        });
        let finicio = $('#fechainicio').val();
        let ffinal = $('#fechafinal').val();

        idate = finicio != '' ? new Date(finicio) : '';
        fdate = ffinal != '' ? new Date(ffinal) : '';

        if (idate != '' && fdate != '') {
            if (fdate < idate) {
                Swal.fire('error',
                    'Fecha fin no puede ser menor a fecha inicio',
                    'error');
                return false;
            }
        }
        $('#frmFiltros').submit();

    });

    $('#btnLimpiar').click(function () {

        $('#runConsulta').val('');
        $('#fechainicio').val('');
        $('#fechafinal').val('');

    });

</script>
@stop
