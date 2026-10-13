<!-- Section Title -->
@extends('transparencia.include.home')
<section class="banner-home2">
    <div class="container">
        <div class="row no-gutters justify-content-between align-items-center">
            <h2 class="text-white mb-0">Escritorio</h2>
            <small class="text-white">
                <time>{{(new DateTime("now", new DateTimeZone('America/Santiago')))->format('H:m d/m/Y')}}0</time>
            </small>
        </div>
    </div>
</section>

<!-- Main Section -->
<section class="main">
    <div class="container">
        <div class="card my-3 shadow-sm">
            <div class="card-body">
                <ul class="nav nav-tabs flex-column flex-lg-row mx-3">
                    <li class="nav-item"><a class="nav-link active" href="escritorio_interno.html">Escritorio</a></li>
                    <li class="nav-item"><a class="nav-link text-black-50" href="consulta_run_lote.html">Consulta por
                            RUN o lote</a></li>
                    <li class="nav-item"><a class="nav-link text-black-50" href="seguimiento_consulta_interno.html">Seguimiento
                            de consultas</a></li>
                </ul>
                <div class="row p-3">
                    <div class="col-12 col-md-4">
                        <div class="card border-success__custom mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-9 col-md-8"><small class="text-success__custom">Nº de
                                            reportes</small>
                                        <h2 class="text-primary__custom">102.599</h2>
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
                                        <h2 class="text-primary__custom">102.599</h2>
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
                                        <h2 class="text-primary__custom">102.599</h2>
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
                    <div class="col-12 col-md-4 d-flex justify-content-end"><a class="btn btn-success mb-2" href="#"
                                                                               role="button"><i
                                class="mdi mdi-file-download"></i> Descargar archivo</a></div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover ">
                                        <thead class="thead-light">
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
                                        <tr>
                                            <td>12</td>
                                            <td>12.345.678-9</td>
                                            <td>Nombre Nombre Apellido Apellido</td>
                                            <td>Cargo</td>
                                            <td>Ministerio de Desarrollo Social y Familia</td>
                                            <td>Interno</td>
                                            <td>08/10/2020</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">12</th>
                                            <td>12.345.678-9</td>
                                            <td>Nombre Nombre Apellido Apellido</td>
                                            <td>Cargo</td>
                                            <td>Ministerio de Desarrollo Social y Familia</td>
                                            <td>Interno</td>
                                            <td>08/10/2020</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">12</th>
                                            <td>12.345.678-9</td>
                                            <td>Nombre Nombre Apellido Apellido</td>
                                            <td>Cargo</td>
                                            <td>Ministerio de Desarrollo Social y Familia</td>
                                            <td>Interno</td>
                                            <td>08/10/2020</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">12</th>
                                            <td>12.345.678-9</td>
                                            <td>Nombre Nombre Apellido Apellido</td>
                                            <td>Cargo</td>
                                            <td>Ministerio de Desarrollo Social y Familia</td>
                                            <td>Interno</td>
                                            <td>08/10/2020</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">12</th>
                                            <td>12.345.678-9</td>
                                            <td>Nombre Nombre Apellido Apellido</td>
                                            <td>Cargo</td>
                                            <td>Ministerio de Desarrollo Social y Familia</td>
                                            <td>Interno</td>
                                            <td>08/10/2020</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">12</th>
                                            <td>12.345.678-9</td>
                                            <td>Nombre Nombre Apellido Apellido</td>
                                            <td>Cargo</td>
                                            <td>Ministerio de Desarrollo Social y Familia</td>
                                            <td>Interno</td>
                                            <td>08/10/2020</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12 d-flex justify-content-center">
                                    <nav aria-label="Página de navegación">
                                        <ul class="pagination custom-pagination">
                                            <li class="page-item">
                                                <a class="page-link" href="#" aria-label="Antes">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#" aria-label="Siguiente">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
