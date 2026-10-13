<!-- Section Title -->
<section class="banner-home2">
    <div class="container">
        <div class="row no-gutters justify-content-between align-items-center">
            <h2 class="text-white mb-0">Consulta por RUN</h2>
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
                <!-- Nav Tabs -->
                @include('transparencia.include.layout.navigation')
                <div class="row p-3">
                    <div class="col-12">
                        <ol class="timeline">
                            <!--paso 1-->
                            <li>
                                <h5>Ingrese RUN a consultar o carga por lote</h5>
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <form class="needs-validation" novalidate>
                                            <div class="form-row">
                                                <div class="col-md-9 mb-3">
                                                    <label class="sr-only" for="run">RUN</label>
                                                    <input type="text" autofocus="autofocus" required="required"
                                                           class="form-control mb-2 mr-sm-2" id="run"
                                                           placeholder="12.345.678-9" size="10">
                                                    <small id="" class="form-text text-muted">Ingrese rut sin puntos y
                                                        dígito
                                                        verificador</small>
                                                    <div class="valid-feedback"> Correcto</div>
                                                    <!-- Cuando hay error <div class="invalid-feedback"> Run no pertenece al registro Social de Hogares </div>-->
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <button type="submit" class="btn btn-primary mb-2 w-100">Consultar
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <!--Caja que aparece una vez consultado run
                                       <div class="card mb-3">
                                            <div class="card-body">
                                              <h5 class="card-title">Nombre Nombre Apellido Apellido</h5>

                                              <p class="card-text"><small>RUN</small> <br>
                                                <strong class="text-primary">12.345.678-9</strong></p>

                                            </div>
                                        </div> -->
                                        <p class="mb-1">Consultar por lote de RUN</p>
                                        <div class="row no-gutters ">
                                            <div class="col-12 col-md-9 pr-md-2">
                                                <a class="btn btn-success mb-2 w-100"
                                                   href="#"
                                                   role="button">
                                                    <i class="mdi mdi-file-download"></i>
                                                    Descargar plantilla para carga por lote
                                                </a>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <button type="submit"
                                                        class="btn btn-primary mb-2 w-100"
                                                        data-toggle="modal"
                                                        data-target="#lote">
                                                    Consultar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="alert alert-primary" role="alert"><i
                                                class="mdi mdi-information"></i> Recuerde que
                                            puede consultar solo por usuarios registrados en el Registro Social de
                                            Hogares, que se
                                            encuentren vigentes durante al menos uno de los últimos 12 meses.
                                        </div>
                                    </div>
                                    <!--caja que aparece cuando se carga por lote-->
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <p>Nombre de la consulta: <strong>Consulta noviembre de 2019,
                                                                comuna 1</strong></p>
                                                        <hr>
                                                    </div>
                                                    <div class="col-12 row no-gutters">
                                                        <p>Nº: <strong>88 consultas</strong></p>
                                                        <p class="mx-md-2">Duplicados: <strong>2 consultas</strong></p>
                                                        <p>Errores: <strong>1 consultas</strong></p>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <table class="table table-bordered table-sm">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">RUN</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <th scope="row">1</th>
                                                                <td>12.345.678-9</td>

                                                            </tr>
                                                            <tr>
                                                                <th scope="row">2</th>
                                                                <td>12.345.678-9</td>

                                                            </tr>
                                                            <tr>
                                                                <th scope="row">2</th>
                                                                <td>12.345.678-9</td>

                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <div class="alert alert-primary" role="alert"><i
                                                                class="mdi mdi-information"></i> Sólo se
                                                            muestran las primeras 3 filas de archivo cargado, a modo de
                                                            ejemplo.
                                                        </div>
                                                        <div class="row no-gutters justify-content-md-between">
                                                            <div class="col-12 col-md-6 pr-md-2">
                                                                <a class="btn btn-success mb-2 w-100" href="#"
                                                                   role="button"><i class="mdi mdi-file-download"></i>
                                                                    Descargar archivo
                                                                </a>
                                                            </div>
                                                            <div class="col-12 col-md-6">
                                                                <a class="btn btn-primary mb-2 w-100 pl-md-2" href="#"
                                                                   role="button"><i class="mdi mdi-reload"></i>
                                                                    Cargar nuevo archivo
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!--paso 2-->
                            <li>
                                <div class="row">
                                    <div class="col-12">
                                        <h5>Seleccione razón de consulta y adjunte justificación</h5>
                                        <div class="form-group">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="razon1" name="customRadio"
                                                       class="custom-control-input">
                                                <label class="custom-control-label" for="razon1">Razon 1</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="razon2" name="customRadio"
                                                       class="custom-control-input">
                                                <label class="custom-control-label" for="razon2">Razon 2</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="razon3" name="customRadio"
                                                       class="custom-control-input">
                                                <label class="custom-control-label" for="razon3">Razon 3</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="razon4" name="customRadio"
                                                       class="custom-control-input">
                                                <label class="custom-control-label" for="razon4">Razon 4</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 pb-2">
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" autofocus="autofocus" required="required"
                                                       class="custom-file-input"
                                                       id="archivo" lang="es">
                                                <label class="custom-file-label" for="archivo">Adjunte
                                                    justificación</label>
                                                <small id="emailHelp" class="form-text text-muted">Adjunte documento de
                                                    justificación de
                                                    solicitud del dato</small></div>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0"><i class="mdi mdi-paperclip"></i> Documento
                                                adjunto_pdf <a
                                                    href="#"> <i class="mdi mdi-delete"></i></a></li>
                                            <li class="list-group-item px-0"><i class="mdi mdi-paperclip"></i> Documento
                                                adjunto_pdf <a
                                                    href="#"> <i class="mdi mdi-delete"></i></a></li>
                                        </ul>
                                        <button type="submit" class="btn btn-primary col-md-3 my-3 w-100"
                                                data-toggle="modal"
                                                data-target="#error">Continuar
                                        </button>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="alert alert-primary" role="alert"><i
                                                class="mdi mdi-information"></i> Ej: E-mail de
                                            jefatura que solicita, E-mail del caso, Oficio o Memo.<br>
                                            En formato, pdf, word, jpg, png, tamaño máximo
                                            2MB.
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!--paso 3-->
                            <li>
                                <div class="row">
                                    <div class="col-12">
                                        <h5>Seleccione reporte</h5>
                                        <form>
                                            <div class="form-group">
                                                <label for="reportes">Reportes</label>
                                                <select autofocus="autofocus" required="required" class="form-control"
                                                        id="reportes">
                                                    <option>1</option>
                                                </select>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-12">
                                        <div class="alert alert-success" role="alert">
                                            <h4 class="alert-heading">Reporte 1</h4>
                                            <p>Aww yeah, you successfully read this important alert message. This
                                                example text is going to
                                                run a bit longer so that you can see how spacing within an alert works
                                                with this kind of
                                                content.</p>
                                            <hr>
                                            <p class="mb-0">
                                                <button type="button" class="btn btn-success btn-sm"><i
                                                        class="mdi mdi-eye"></i> Ver ejemplo
                                                    de reporte
                                                </button>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!--paso 4-->
                            <li>
                                <div class="col-12">
                                    <h5>Resumen de la consulta</h5>
                                    <div class="alert alert-primary" role="alert">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <p><small>Archivo a consultar</small><br>
                                                    <strong>RUN_101202020.csv</strong></p>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <p><small>Razón consulta</small><br>
                                                    Razón 1</p>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <p><small>Nombre consulta</small><br>
                                                    Consulta noviembre de 2019, comuna 1</p>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <p><small>Documento</small><br>
                                                    Archivo_adjunto.pdf</p>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <p><small>Reporte</small><br>
                                                    <strong>reporte 1</strong></p>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit"
                                            class="btn btn-outline-secondary">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-primary"
                                            data-toggle="modal"
                                            data-target="#declaracion">
                                        Solicitar
                                    </button>
                                </div>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modals -->
@include('transparencia.include.consulta_run_modals')
