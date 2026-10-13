<!-- Section Title -->
@extends('transparencia.main')
@section('content')
<section class="banner-home2">
    <div class="container">
        <div class="row no-gutters justify-content-between align-items-center">
            <h2 class="text-white mb-0">Escritorio</h2>
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
                    <div class="col-12 col-md-4">
                        <div class="card border-success__custom mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-9 col-md-8"><small class="text-success__custom">Nº de
                                            reportes</small>
                                        <h2 class="text-primary__custom">{{ $reportes }}</h2>
                                        <small class="text-success__custom">Total</small></div>
                                    <div class="col-3 col-md-4"><i
                                            class="mdi mdi-poll-box text-primary__custom mdi-48px"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card border-success__custom mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-9 col-md-8"><small class="text-success__custom">Nº de
                                            consultas</small>
                                        <h2 class="text-primary__custom">{{ $internos }}</h2>
                                        <small class="text-success__custom">Usuarios internos</small></div>
                                    <div class="col-3 col-md-4"><i
                                            class="mdi mdi-account text-primary__custom mdi-48px"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card border-success__custom mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-9 col-md-8"><small class="text-success__custom">Nº de
                                            consultas</small>
                                        <h2 class="text-primary__custom">{{ $externos }}</h2>
                                        <small class="text-success__custom">Usuarios externos</small></div>
                                    <div class="col-3 col-md-4"><i
                                            class="mdi mdi-account-supervisor text-primary__custom mdi-48px"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row p-3">
                    <div class="col-12 col-md-8">
                        <h5>¿Qué usuarios son los que más consultan?</h5>
                    </div>
                    <div class="col-12 col-md-4 d-flex justify-content-end">
                        <button id="" name="" type="button" class="btn btn-success mb-2" onclick="procesaDescargar()">
                            <i class="mdi mdi-file-download"></i>Descargar archivo</button>
                    </div>
                    <div class="col-12">
                        <div class="card shadow p-3 mt-2 table-responsive">
                            <table id="table_escritorio" class="table table-striped table__bg">
                                <thead>
                                <tr>
                                    <th scope="col">Nº Reportes</th>
                                    <th scope="col">RUN</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Cargo</th>
                                    <th scope="col">Dependencia</th>
                                    <th scope="col">Perfil</th>
                                    <th scope="col">Último acceso</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($lstConsultas as $auxConsultas)
                                    <tr>
                                        <td>{{$auxConsultas->reportes}}</td>
                                        <td>{{$auxConsultas->rut}}</td>
                                        <td>{{$auxConsultas->nombre}}</td>
                                        <td> @if(isset($auxConsultas->cargo))
                                                {{$lstCargo[$auxConsultas->cargo]}}
                                            @endif
                                        </td>
                                        <td> @if(isset($auxConsultas->dependencia))
                                                {{$lstDependencias[$auxConsultas->dependencia]}}
                                            @endif
                                        </td>
                                        <td> @if(isset($auxConsultas->perfil))
                                                {{$lstPerfil[$auxConsultas->perfil]}}</td>
                                        @endif
                                        <td>
                                            @if(isset($auxConsultas->ultimo_acceso))
                                                @php
                                                    $fecha = explode(' ', $auxConsultas->ultimo_acceso);
                                                    echo ($fecha[0]);
                                                @endphp
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div><!-- End Requests Table -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <script>
        let loading;
        $(document).ready( function () {
            $('#table_escritorio').DataTable({
                "processing": false,
                "responsive": true,
                "pageLength": 5,
                "lengthMenu": [5, 10, 25, 50, 75, 100],
                "order": [[ 0, "desc" ]],
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
        } );
        $("#nav-escritorio").attr('class', 'nav-link active');

        //-----------------------------------------
        function procesaDescargar() {
            let form = new FormData();
            form.append("_token", "{{ csrf_token() }}");
            let urlDescarga = "{{ route('DescargaResultadosEscritorio') }}";
            //loading.close();
            descargar(urlDescarga, form);
        }
        //---------------------
        function descargar(urlDescarga, form){
            let req = new XMLHttpRequest();
            req.open("POST", urlDescarga, true);
            req.responseType = "blob";
            req.addEventListener('load', archivoDocumentoOnLoad);
            req.send(form);
        }
        //---------------------
        function descargarArchivo(XMLHttpRequestObject)
        {
            let contentType = XMLHttpRequestObject.getResponseHeader("content-type");
            let fileName;
            if (XMLHttpRequestObject.getResponseHeader("content-disposition")) {  // IE/EDGE seems not returning some response header
                var contentDisposition = XMLHttpRequestObject.getResponseHeader("content-disposition");
                fileName = contentDisposition.substring(contentDisposition.indexOf("=") + 1);
            } else {
                fileName = "documento." + contentType.substring(contentType.indexOf("/") + 1);
            }
            let blob = XMLHttpRequestObject.response;
            if (window.navigator.msSaveOrOpenBlob) {
                window.navigator.msSaveOrOpenBlob(new Blob([blob], {type: contentType}), fileName); // Internet Explorer
            } else {
                let baseApi = window.URL.createObjectURL(blob);
                let a = document.createElement("a");
                a.style = "display: none";
                a.href = baseApi;
                a.download = fileName;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(baseApi);
            }
        }
        //---------------------
        function archivoDocumentoOnLoad(event)
        {
            let req = event.target,
                contentType = req.getResponseHeader("content-type");
            if (contentType === 'application/json' || contentType === 'text/html; charset=UTF-8') {
                Swal.fire('Error',
                    'Error al descargar el documento',
                    'error');
            } else {
                descargarArchivo(req);
            }
        }
        //---------------------
    </script>
@stop

