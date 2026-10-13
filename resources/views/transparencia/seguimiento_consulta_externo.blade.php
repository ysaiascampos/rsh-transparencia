<!-- Section Title -->
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
    <div class="container-fluid">
        <div class="card my-3 shadow-sm">
            <div class="card-body">
                <ul class="nav nav-tabs flex-column flex-lg-row mx-3">
                    <li class="nav-item"> <a class="nav-link text-black-50 " href="consulta_run.html">Consulta por RUN</a> </li>
                    <li class="nav-item"> <a class="nav-link active" href="seguimiento_consulta_externo.html">Seguimiento de
                            consultas</a> </li>
                </ul>
                <div class="row p-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-5 mb-3 mb-md-0">
                                        <div class="form-row align-items-center">
                                            <div class="col-md-3 d-flex mb-1"><i class="mdi mdi-magnify mr-1"></i> Buscar por RUN</div>
                                            <div class="col-md-9">
                                                <form class="needs-validation" novalidate>
                                                    <div class="form-row">
                                                        <div class="col-md-8 mb-2">
                                                            <label class="sr-only" for="run">RUN</label>
                                                            <input type="text" autofocus="autofocus" class="form-control" id="run"
                                                                   placeholder="12345678-9" autocomplete="on">
                                                            <small id="run-info" class="form-text text-muted">Ingrese rut sin puntos y dígito
                                                                verificador</small>
                                                            <div class="valid-feedback"> Correcto </div>
                                                            <!-- Cuando hay error <div class="invalid-feedback"> Run no pertenece al registro Social de Hogares </div>-->
                                                        </div>
                                                        <button type="submit" class="btn btn-primary mb-2 col-md-3 mx-1">Buscar</button>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7 mb-3 mb-md-0">
                                        <div class="form-row align-items-center">
                                            <div class="col-md-2 d-flex mb-1"><i class="mdi mdi-magnify mr-1"></i> Buscar por fechas</div>
                                            <div class="col-md-10">
                                                <form class="form-row">
                                                    <div class="col-md-3">
                                                        <label class="sr-only" for="fechainicio">Fecha de inicio</label>
                                                        <input type="date" class="form-control mb-2 mb-md-0" id="fechainicio" name="fecha"
                                                               value="" min="2020-01-01" max="2030-12-31">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="sr-only" for="fechafinal">Fecha final</label>
                                                        <input type="date" class="form-control mb-2 mb-md-0" id="fechafinal" name="fecha" value=""
                                                               min="2020-01-01" max="2030-12-31">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary mb-2 col-md-2 mx-1">Buscar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 p-3">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">RUN</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Razón consulta</th>
                                    <th scope="col">Reporte</th>
                                    <th scope="col">Meses</th>
                                    <th scope="col">Fecha solicitud</th>
                                    <th scope="col">Justificación</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Resumen</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>12.345.678-9</td>
                                    <td>Nombre Nombre Apellido Apellido</td>
                                    <td>Respuesta plataforma de transparencia</td>
                                    <td>Reporte básico</td>
                                    <td>3 meses</td>
                                    <td>08-10-2020</td>
                                    <td><button type="button" class="btn btn-info btn-sm"><i class="mdi mdi-file-download"></i>
                                            Justificación</button></td>
                                    <td>Enviado</td>
                                    <td>
                                        <div class="d-flex flex-column flex-lg-row">
                                            <button type="button" class="btn btn-success btn-sm btn-table mr-lg-2">
                                                <i class="mdi mdi-file-download"></i>
                                                Reporte
                                            </button>
                                            <button type="button" class="btn btn-primary btn-sm btn-table">
                                                <i class="mdi mdi-eye"></i>
                                                Ver
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 d-flex justify-content-center">
                            <nav aria-label="Página de navegación">
                                <ul class="pagination">
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
</section>
