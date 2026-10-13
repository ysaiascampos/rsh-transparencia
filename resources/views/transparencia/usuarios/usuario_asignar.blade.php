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
                                        <div class="col-md-12 mb-3 mb-md-0">
                                            <div class="form-row align-items-center">
                                                <div class="col-md-3 d-flex mb-1"><i class="mdi mdi-key"></i> Ingresar
                                                    Run
                                                </div>
                                                <div class="col-md-9">
                                                    <form class="needs-validation" novalidate>
                                                        <div class="form-row">
                                                            <div class="col-md-8 mb-2">
                                                                <label class="sr-only" for="run">RUN</label>
                                                                <input type="text" autofocus="autofocus"
                                                                       class="form-control" id="runConsulta"
                                                                       placeholder="12.345.678-9" autocomplete="on">
                                                                {{--<small id="runConsulta-info" class="form-text text-muted">Ingrese rut sin puntos y dígito
                                                                    verificador</small>--}}
                                                                <div class="valid-feedback"> Correcto</div>
                                                                <!-- Cuando hay error <div class="invalid-feedback"> Run no pertenece al registro Social de Hogares </div>-->
                                                            </div>
                                                            <div class="col-md-4 mb-2">
                                                                <button type="button" onclick="buscarUsuarioSSO()"
                                                                        class="btn btn-primary mb-12 col-md-12 mx-1">
                                                                    Agregar
                                                                </button>
                                                            </div>
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
                                <div class="tab-pane fade show active" id="pills-run" role="tabpanel"
                                     aria-labelledby="pills-run-tab">
                                    <div class="table-responsive">
                                        @if (@$message)
                                            <div class="alert alert-success">{{$message}}
                                                <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        @endif
                                        <table id="usuariosTable" class="table table-hover">
                                            <thead class="thead-light">
                                            <tr>
                                                <th scope="col">Rut</th>
                                                <th scope="col">Nombre</th>
                                                <th scope="col">Ultimo Acceso</th>
                                                <th scope="col">Perfil</th>
                                                <th scope="col">Cargo</th>
                                                <th scope="col">Dependencia</th>
                                                <th scope="col">Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($lstUsuarios as $auxUsuarios)
                                                <tr>
                                                    {{--                                                <form id="loginform" class="form-horizontal" role="form" action="../cambiar_perfil"   method="post">--}}
                                                    <td>{{\App\Http\Controllers\Utiles::ponePuntosNumero($auxUsuarios->rut)}}</td>
                                                    <td>{{$auxUsuarios->nombre}}</td>
                                                    <td>{{$auxUsuarios->ultimo_acceso}}</td>
                                                    <td>{{$lstPerfiles[$auxUsuarios->perfil]}}</td>
                                                    <td>{{$lstCargos[$auxUsuarios->cargo]}}</td>
                                                    <td>{{$lstDependencias[$auxUsuarios->dependencia]}}</td>
                                                    {{--<td ><select class="form-control col-lg-12 col-sm-12" id="perfil" name="perfil">
                                                                @foreach($lstPerfiles as $auxPerfil )
                                                                    <option
                                                                        @if($auxUsuarios->perfil == $auxPerfil->id_perfil )
                                                                        selected = "selected"
                                                                        @endif
                                                                        value ='{{$auxPerfil->id_perfil}}' >{{$auxPerfil->nombre_perfil}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td ><select class="form-control col-lg-12 col-sm-12" id="cargo" name="cargo">
                                                                @foreach($lstCargos as $aux )
                                                                    <option
                                                                        @if($auxUsuarios->cargo == $aux->id_cargo )
                                                                        selected = "selected"
                                                                        @endif
                                                                        value ='{{$aux->id_cargo}}' >{{$aux->nombre_cargo}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td ><select class="form-control col-lg-12 col-sm-12" id="dependencia" name="dependencia">
                                                                @foreach($lstDependencias as $aux )
                                                                    <option
                                                                        @if($auxUsuarios->dependencia == $aux->id_dependencia )
                                                                        selected = "selected"
                                                                        @endif
                                                                        value ='{{$aux->id_dependencia}}' >{{$aux->nombre_dependencia}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>--}}

                                                    <td>
                                                        <div class="d-flex flex-column flex-lg-row">
                                                            <input type="hidden"
                                                                   id="rut{{$auxUsuarios->rut}}"
                                                                   name="rut{{$auxUsuarios->rut}}"
                                                                   value="{{$auxUsuarios->rut}}">
                                                            <button id="btn{{$auxUsuarios->rut}}"
                                                                    name="btn{{$auxUsuarios->rut}}"
                                                                    type="button"
                                                                    class="btn btn-success btn-sm btn-group-sm btn-table col-lg-12 col-sm-12"
                                                                    onclick="muestraModal('{{$auxUsuarios->rut}}')">
                                                                <i class="mdi mdi-account-edit"></i>
                                                                Modificar
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
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

        <div class="modal fade" id="detalleUsuarioModal" tabindex="-1" role="dialog"
             aria-labelledby="detalleUsuarioModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="detalleUsuarioModalTitle"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="alert alert-danger" id="msgModal" role="alert"
                         style="margin:0 auto; display:none; width: 95%;">...
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="guardarUsuarioModal();">
                            Guardar
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </section>
    <script>
        $(document).ready(function () {
            $('#usuariosTable').DataTable({
                "processing": false,
                "responsive": true,
                "pageLength": 5,
                "lengthMenu": [5, 10, 25, 50, 75, 100],
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
        });

        function muestraModal(run) {
            $.ajax({
                type: 'POST',
                cache: false,
                url: "../detalleUsuario",
                data: {
                    _token: "{{ csrf_token() }}",
                    rut: run
                },
                success: function (data) {
                    console.log(data);
                    let lstPerfiles = JSON.parse(data.lstPerfiles);
                    let lstCargos = JSON.parse(data.lstCargos);
                    let lstDependencias = JSON.parse(data.lstDependencias);
                    $('#detalleUsuarioModalTitle').html(data.rut + ' - ' + data.nombre);
                    let html = '<form id="formaModal">';
                    html += '<input type="hidden" name="rutModal" id="rutModal" value="' + data.rut + '"/>';
                    html += '<div class="row"> ' +
                        '<div class="col-lg-6">' +
                        '<div class="row align-items-center">' +
                        '<div class="col-lg-12"><strong>Perfil:</strong>';
                    // Definicion de HTML para perfiles
                    html += '<select class="form-control" id="usuarioPerfil" name="usuarioPerfil">';
                    Object.keys(lstPerfiles).forEach(key => {
                        // console.log(key,data.perfil,key==data.perfil);
                        if (key == data.perfil) {
                            html += '<option value="' + key + '" selected>' + lstPerfiles[key] + '</option>';
                        } else {
                            html += '<option value="' + key + '">' + lstPerfiles[key] + '</option>';
                        }
                    });
                    html += '</select>';
                    html += '</div></div></div>';
                    html += '<div class="col-lg-6">' +
                        '<div class="row align-items-center">' +
                        '<div class="col-lg-12"><strong>Cargo:</strong>';
                    // Definicion de HTML para Cargos
                    html += '<select class="form-control" id="usuarioCargo" name="usuarioCargo">';
                    Object.keys(lstCargos).forEach(key => {
                        // console.log(key,data.cargo,key==data.cargo);
                        if (key == data.cargo) {
                            html += '<option value="' + key + '" selected>' + lstCargos[key] + '</option>';
                        } else {
                            html += '<option value="' + key + '">' + lstCargos[key] + '</option>';
                        }
                    });
                    html += '</select>';
                    html += '</div></div></div></div>';


                    html += '<div class="row">' +
                        '<div class="col-lg-6">' +
                        '<div class="row align-items-center">' +
                        '<div class="col-lg-12"><strong>Dependencia:</strong>';
                    // Definicion de HTML para Dependencias
                    html += '<select class="form-control" id="usuarioDependencia" name="usuarioDependencia">';
                    Object.keys(lstDependencias).forEach(key => {
                        // console.log(key,data.dependencia,key==data.dependencia);
                        if (key == data.dependencia) {
                            html += '<option value="' + key + '" selected>' + lstDependencias[key] + '</option>';
                        } else {
                            html += '<option value="' + key + '">' + lstDependencias[key] + '</option>';
                        }
                    });
                    html += '</select>';
                    html += '</div></div></div>';


                    html += '<div class="col-lg-6">' +
                    '<div class="row align-items-center">' +
                    '<div class="col-lg-12"><strong>Estado:</strong>';
                    // Definicion de HTML para Dependencias
                    html += '<select class="form-control" id="usuarioEstado" name="usuarioEstado">';
                    if (data.estado == 1) {
                        html += '<option value="1" selected>Activo</option>';
                        html += '<option value="0">Inactivo</option>';
                    } else {
                        html += '<option value="1">Activo</option>';
                        html += '<option value="0" selected>Inactivo</option>';
                    }
                    html += '</select>';
                    html += '</div></div></div>';

                    html += '</div></div></div>';


                    html += '' +
                        '<div class="row align-items-center">' +
                        '<div class="col-lg-12"><strong>Correo:</strong>';
                    // Definicion de HTML para Correo
                    html += '<div class="row align-items-center">';
                    html +='<div class="col-lg-6"><input class="form-control" type="text" value="' + data.correoUsuario + '" id="usuarioCorreo1" name="usuarioCorreo1" onkeyup="this.value = NumText(this.value)"/></div><div class="col-lg-1">@</div>';
                    html += '<div class="col-lg-5"><select class="form-control" id="usuarioCorreo2" name="usuarioCorreo2">';
                    if (data.dominioFuera) {
                        html += '<option value="-1" selected disabled hidden>' + data.correoDominio + '</option>';
                    }
                    Object.keys(data.domains).forEach(key => {
                        // console.log(data.domains[key],data.correoDominio,data.domains[key]==data.correoDominio);
                        if (data.domains[key] == data.correoDominio) {
                            html += '<option value="' + data.domains[key] + '" selected>' + data.domains[key] + '</option>';
                        } else {
                            html += '<option value="' + data.domains[key] + '">' + data.domains[key] + '</option>';
                        }
                    });
                    html += '</select>';
                    html += '</div></div>';
                    html += '</div></div></div>';

                    html += '</form>';

                    $('#detalleUsuarioModal').find('.modal-body').html(html);
                    $('#detalleUsuarioModal').modal('show');
                },
                error: function (error) {
                    console.log(error);
                    Swal.fire('Error',
                        'Error inesperado, intente nuevamente',
                        'error');
                }
            });
        }

        function guardarUsuarioModal() {
            let dataFrm = $('#formaModal').serializeArray();
            dataFrm.push({name: "_token",value : "{{ csrf_token() }}"});
            let usuarioCorreo = $('#usuarioCorreo1').val();
            let dominioCorreo = $('#usuarioCorreo2').val();

            if (usuarioCorreo != '' && (dominioCorreo != -1 && dominioCorreo != null)) {
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: "../actualizaUsuario",
                    data: dataFrm,
                    success: function (data) {
                        console.log(data);

                        $('#detalleUsuarioModal').find('.modal-body').html("");
                        $('#detalleUsuarioModal').modal('hide');

                        Swal.fire("Guardado",
                            "Los datos fueron modificados exitosamente",
                            "success");

                        window.location.reload();
                    },
                    error: function (error) {
                        console.log(error);
                        Swal.fire('Error',
                            'Error inesperado, intente nuevamente',
                            'error');
                    }
                });
            } else {
                Swal.fire('warning',
                    'Complete los datos correctamente antes de guardar. Correo Invalido.',
                    'warning');
            }
        }

        function buscarUsuarioSSO() {
            let datos = [];
            let rutconguion = $("#runConsulta").val();
            let rutsinguion = rutconguion.replaceAll('.', '').split('-');
            $.ajax({
                type: 'POST',
                cache: false,
                url: "../buscarUsuario",
                data: {
                    _token: "{{ csrf_token() }}",
                    rut: rutsinguion[0]
                },
                success: function (data) {
                    location.reload();
                },
                error: function (error) {

                    alert("error en ingreso")
                }
            });
        }

        $('#detalleUsuarioModal').on('hidden.bs.modal', function (e) {
            $('#detalleUsuarioModalTitle').html("");
            $('#detalleUsuarioModal').find('.modal-body').html("");
        })
    </script>
@stop
