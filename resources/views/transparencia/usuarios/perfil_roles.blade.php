@extends('transparencia.main')
@section('content')
<section class="banner-home2">
    <div class="container">
        <div class="row no-gutters justify-content-between align-items-center">
            <h2 class="text-white mb-0">Mantendor de Usuarios</h2>
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
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-8 mb-6 mb-md-0">
                                        <div class="form-row align-items-center">
                                            <div class="col-md-3 d-flex mb-1"><i class="mdi mdi-magnify mr-1"></i> Seleccione Perfil</div>
                                            <div class="col-md-9">
                                                <form class="needs-validation" novalidate>
                                                    <div class="form-row">
                                                        <div class="col-md-8 mb-2">

                                                            <select class="form-control col-lg-6 col-sm-6" id="perfil" name="perfil">
                                                                @foreach($lstPerfiles as $auxPerfil )
                                                                    <option
                                                                        value ='{{$auxPerfil->id_perfil}}' >{{$auxPerfil->nombre_perfil}}
                                                                    </option>>
                                                                @endforeach
                                                            </select>
                                                            <!-- Cuando hay error <div class="invalid-feedback"> Run no pertenece al registro Social de Hogares </div>-->
                                                        </div>
                                                        <button type="submit" class="btn btn-primary mb-3 col-md-3 mx-1">Seleccionar </button>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 p-3">
                        <div class="tab-content" id="pills-tabContent">
                            <!--tabla por run-->
                            <div class="tab-pane fade show active" id="pills-run" role="tabpanel" aria-labelledby="pills-run-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Rol</th>
                                            <th scope="col">Descripcion</th>
                                            <th scope="col">Codigo</th>
                                            <th scope="col">Acceso</th>
                                            <th scope="col">Acciones</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Escritorio</td>
                                            <td>Acceso a Plataforma de Escritorio</td>
                                            <td>ESC</td>
                                            <td>Autorizado</td>
                                            <td>
                                                <div class="d-flex flex-column flex-lg-row">
                                                    <button type="button" class="btn btn-success btn-sm btn-table mr-lg-2">
                                                        <i class="mdi mdi-lock-alert"></i>
                                                        Quitar Acceso
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Consulta Rut</td>
                                            <td>Acceso a Plataforma de Escritorio</td>
                                            <td>ESC</td>
                                            <td>Autorizado</td>
                                            <td>
                                                <div class="d-flex flex-column flex-lg-row">
                                                    <button type="button" class="btn btn-success btn-sm btn-table mr-lg-2">
                                                        <i class="mdi mdi-lock-alert"></i>
                                                        Quitar Acceso
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Consulta Lote</td>
                                            <td>Acceso a Plataforma de Escritorio</td>
                                            <td>ESC</td>
                                            <td>Autorizado</td>
                                            <td>
                                                <div class="d-flex flex-column flex-lg-row">
                                                    <button type="button" class="btn btn-success btn-sm btn-table mr-lg-2">
                                                        <i class="mdi mdi-lock-alert"></i>
                                                        Quitar Acceso
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Seguimiento Externo</td>
                                            <td>Acceso a Plataforma de Escritorio</td>
                                            <td>ESC</td>
                                            <td>Autorizado</td>
                                            <td>
                                                <div class="d-flex flex-column flex-lg-row">
                                                    <button type="button" class="btn btn-success btn-sm btn-table mr-lg-2">
                                                        <i class="mdi mdi-lock-alert"></i>
                                                        Quitar Acceso
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Seguimiento Interno</td>
                                            <td>Acceso a Plataforma de Escritorio</td>
                                            <td>ESC</td>
                                            <td>Autorizado</td>
                                            <td>
                                                <div class="d-flex flex-column flex-lg-row">
                                                    <button type="button" class="btn btn-success btn-sm btn-table mr-lg-2">
                                                        <i class="mdi mdi-lock-alert"></i>
                                                        Quitar Acceso
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Seguimiento Interno</td>
                                            <td>Acceso a Plataforma de Escritorio</td>
                                            <td>ESC</td>
                                            <td>Autorizado</td>
                                            <td>
                                                <div class="d-flex flex-column flex-lg-row">
                                                    <button type="button" class="btn btn-success btn-sm btn-table mr-lg-2">
                                                        <i class="mdi mdi-lock-alert"></i>
                                                        Quitar Acceso
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Seguimiento Interno</td>
                                            <td>Acceso a Plataforma de Escritorio</td>
                                            <td>ESC</td>
                                            <td>Autorizado</td>
                                            <td>
                                                <div class="d-flex flex-column flex-lg-row">
                                                    <button type="button" class="btn btn-success btn-sm btn-table mr-lg-2">
                                                        <i class="mdi mdi-lock-alert"></i>
                                                        Quitar Acceso
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <!--tabla por lote-->

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $("#nav-perfiles").attr('class', 'nav-link active');
</script>
@stop
