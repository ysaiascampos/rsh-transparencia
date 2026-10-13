@extends('transparencia.main')
@section('content')<!-- Section Title -->
<section class="banner-home2">
    <div class="container">
        <div class="row no-gutters justify-content-between align-items-center">
            <h2 class="text-white mb-0">Consultas por Run</h2>
            <small class="text-white">
                <time>{{(new DateTime("now", new DateTimeZone('America/Santiago')))->format('H:m d/m/Y')}}</time>
            </small>
        </div>
    </div>
</section>
<section class="main">
    <div class="container">
        <div class="card my-3 shadow-sm">
            <div class="card-body">
                @include('transparencia.include.layout.navigation')
                <div class="row p-3">
                    <div class="col-12">
                        <!--mensaje perfil publico-->
                        <div class="alert alert-success p-5 text-center" role="alert">
                            <h4 class="alert-heading">Solicitud exitosa</h4>
                            <p>Su solicitud ha sido realizada con éxito. <br>
                                Pronto recibirá los datos solicitados al email <strong>{{Session::get("heskUserEmail")}}</strong></p>
                            <h1><i class="mdi mdi-email"></i> </h1>
                        </div>
                        <!--mensaje perfil interno-->
                        <div class="alert alert-info p-5 text-center" role="alert">
                            <h4 class="alert-heading">Solicitud exitosa</h4>
                            <p>Su solicitud ha sido realizada con éxito. <br>
                                Descargue reporte.</p>
                            <button type="button" class="btn btn-success btn-sm"
                                    onclick="getReporte({{$_GET['id']}},'{{$_GET['nombreArchivo']}}')">
                                <i class="mdi mdi-file-download"></i>
                                Reporte
                            </button>
                        </div>
                        <div style="text-align: center;"><button type="button" class="btn btn-outline-secondary" onclick="volver()">Volver</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <script>
        function volver()
        {
            location.replace("{{ route('consulta_run') }}");
        }
    </script>
@stop
