@extends('transparencia.main')
@section('content')<!-- Section Title -->
<!--style del componente de carga de archivos-->
<style>
    .swal-input-btn.checked{
        box-shadow: inset 610px 0 0 0 #28a745;
    }
</style>
<link rel="stylesheet" href="{!! asset('assets/css/ComponenteArchivos.css') !!}">
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
            <form id="saveSolicitud" name="saveSolicitud"  enctype="multipart/form-data">
                <input type="hidden" name= "url" id="url" value="{{public_path()}}">
                <input type="hidden" name= "nombreConsulta" id="nombreConsulta" value="">
                @csrf
                <div class="card-body">
                    <!-- Nav Tabs -->
                    @include('transparencia.include.layout.navigation')
                        <div class="row p-3">
                        <div class="col-12">
                            <ol class="timeline">
                                <!--paso 1-->
                                <li>
                                    <h5>Ingrese RUN a consultar</h5>
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <form class="needs-validation" novalidate>
                                                <div class="form-row">
                                                    <div class="col-md-9 mb-3">
                                                        <label class="sr-only" for="run">RUN</label>
                                                        <input type="text" required="required"
                                                               class="form-control mb-2 mr-sm-2" id="runConsulta"
                                                               placeholder="12.345.678-9" maxlength="14">
                                                        <small id="" class="form-text text-muted">Ingrese rut con dígito
                                                            verificador</small>
                                                        <div class="valid-feedback"> Correcto</div>
                                                        <!-- Cuando hay error <div class="invalid-feedback"> Run no pertenece al registro Social de Hogares </div>-->
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <button type="button" id="consultarBoton" class="btn btn-primary mb-2 w-100">Consultar
                                                        </button>
                                                    </div>
                                                    <a id="limpiarRun" class="button" style="font-size: 20px; color: #bd2130; position: relative;
                                                    right: 18px;top: -70px" title="Limpiar el campo Run" href="#">
                                                        <span class="mdi mdi-broom"></span>
                                                    </a>
                                                </div>
                                            </form>
                                            <!--
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

                                        //caja que aparece cuando se carga por lote
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
                                            -->
                                        </div>
                                    </div>
                                </li>
                                <!--paso 2-->
                                <li id="paso2" class="panel-collapse collapse">
                                    @include('transparencia.include.componenteArchivo')
                                </li>
                                <!--paso 3-->
                                <li id="paso3" class="collapse">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5>Seleccione reporte</h5>
                                            <form>
                                                <div class="form-group">
                                                    <label for="reportes">Reportes</label>
                                                    <select autofocus="autofocus" required="required" class="form-control col-6" id="reportes" onclick="mostrarReporte()">
                                                        <option value="-99" selected>--Seleccione--</option>
                                                        @foreach($lstReportes as $reporte)
                                                            <option value="{{$reporte->id_reporte}}" data-nombreReporte="{{$reporte->nombre_reporte}}" data-textoDetalle="{{$reporte->descripcion}}" data-url="{{$reporte->archivo}}">{{$reporte->nombre_reporte}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </form>
                                        </div>
                                        <div id="detalleReporte" class="col-12" style="display: none"></div>
                                        <div class="col-6">
                                        <button type="button" id="continuarPaso3"
                                                class="btn btn-primary col-md-3 my-3 w-100">Continuar</button>
                                        <!--<button type="button" id="volverPaso2" style="margin-left: 20px"
                                                class="btn btn-info col-md-4 my-4 w-100">Volver Paso 2</button>-->
                                        </div>
                                    </div>
                                </li>
                                <!--paso 4-->
                                <li id="paso4" class="collapse">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5>Resumen de la consulta</h5>
                                            <div class="alert alert-primary" role="alert">
                                                <div class="row">
                                                    <div class="col-12 col-md-6">
                                                        <p><small>Razón consulta</small><br><strong id="labelRazon"></strong></p>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <p><small>Nombre consulta</small><br><strong id="labelConsulta"></strong></p>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <p><small>Documento</small><br><strong id="labelDocumento"></strong></p>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <p><small>Reporte</small><br><strong id="labelReporte"></strong></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <a href="{{ route('consulta_run') }}" class="btn btn-secondary">Cancelar</a>
                                            <!--<button type="button" id="volverPaso3" style="margin-left: 20px"
                                                    class="btn btn-info">Volver Paso 3</button>-->
                                            <button type="button"
                                                    style="margin-left: 20px"
                                                    class="btn btn-primary"
                                                    id="generarSolicitud"
                                                    name="generarSolicitud">
                                                Solicitar Reporte
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
{{ HTML::script('assets/js/componenteArchivo.js') }}
{{ HTML::script('assets/js/consultaRun.js') }}
<script>
    $("#nav-consulta").attr('class', 'nav-link active');

    function desactivarRazones(){
        //deshabilitar razon
        $("input[id^='razon']").each(function( index,element ) {
            $("#razon"+(index+1)).attr('disabled',true);
        });
    }

    function habilitaRazones(){
        //habilitar razon
        $("input[id^='razon']").each(function( index,element ) {
            $("#razon"+(index+1)).attr('disabled',false);
        });
    }

    //---------
    function save(){

        var run =  $("#runConsulta").val();
        var reportes = $("#reportes").val();
        var runconsulta = run.replaceAll(".","");
        let runconsultasindv =  runconsulta.toString().split("-");
        let campo = $('input:radio[name=customRadio]:checked').attr('id');
        let index = campo.replace('razon','');
        let idRazon = $("#"+campo).val();
        let txtNombreConsulta = $("#nombreConsulta").val();
        let valorOpcionalTxt= $("#"+campo).data('opcionaltxt');
        let txtRazon ='';
        if(valorOpcionalTxt == 1) {
            txtRazon = $("#txtOpcional" + index).val();
        }
        let loading;
        let arraySolicitud = $('#saveSolicitud').serializeArray();
        //console.log('arraySolicitud: '+ arraySolicitud);
        var formData = new FormData();
        formData.append("_token", "{{ csrf_token() }}");
        formData.append("run", runconsultasindv[0]);
        formData.append("dv", runconsultasindv[1]);
        formData.append("reporte", reportes);
        formData.append("razon", idRazon);
        formData.append("txtrazon", txtRazon);
        formData.append("nombreConsulta", txtNombreConsulta);
        let fileInput = $("#file").attr("name");
        //console.log('fileInput: '+fileInput);
        if (formData.has(fileInput)) {
            formData.delete(fileInput);
        }
        var razonDocs = [];
        for (let i = 0, l = dFiles.length; i < l; i++) {
            razonDocs.push(JSON.stringify({razon:dFiles[i].idRazon,txt:dFiles[i].txtRazon}));
            formData.append('docs[]', dFiles[i]);
            formData.append('detalleDocs[]',razonDocs[i])
        }
        $.ajax({
            url: '{{asset("guardar_solicitud")}}',
            type: "post",
            dataType: "html",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                loading = Swal.fire({
                    title: "Generando informe. Espere unos minutos por favor ...",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    onOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function (response) {
                let resp = JSON.parse(response);
                loading.close();
                if( resp.id_solicitud && resp.nombreArchivo ) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Generada con Exito!',
                        showConfirmButton: false,
                        timer: 2500
                    })
                    location.replace("{{ route('solicitud_descarga') }}?id="+resp.id_solicitud+"&nombreArchivo="+resp.nombreArchivo);
                }else{
                    Swal.fire({
                        icon: 'error',
                        text: resp.error,
                    })
                }
            },
            error: function (error) {
                let resp = JSON.parse(error.responseText);
                loading.close();
                loading = Swal.fire({
                    icon: 'error',
                    text: resp.error,
                });
            }
        });
    }

    //-----------
    function habilitar(selec){

        let esOpcionaltxt = selec.dataset.opcionaltxt;
        let valorSelec = selec.id.replace('razon','');
        //limpio todos los campos opcionalestxt
        $("input[id^='razon']").each(function( index,element ) {
            if( $("#txtOpcional"+(index+1)).length > 0){
                $("#txtOpcional"+(index+1)).attr('disabled',true);
                $("#txtOpcional"+(index+1)).val('');
            }
        });
        //habilito txtopcional
        if(esOpcionaltxt == 1){
            $("#txtOpcional"+valorSelec).attr('disabled',false);
        }

    }
    //-----------
    function validarPaso2() {

        var arrayValidacion = [];
        //if( $("#saveSolicitud input[name='customRadio']:radio").is(':checked'))
        if( $('input:radio[name=customRadio]:radio').is(':checked'))
        {
            let campo = $('input:radio[name=customRadio]:checked').attr('id');
            let index = campo.replace('razon','');
            let valorOpcionalTxt= $("#"+campo).data('opcionaltxt');
            if(valorOpcionalTxt == 1){
                if( $("#txtOpcional"+index).val() == ""){
                    arrayValidacion.push(0,'Debe Ingresar medio de Justificación Otros, un valor valido');
                }else{
                    //valida si hay archivos para adjuntar
                    if(dFiles.length > 0){
                        arrayValidacion.push(1,'');
                    }else{
                        arrayValidacion.push(0,'Debe adjuntar un documento de justificación');
                    }
                }
            }else{
                console.log('Archivos : ' + dFiles );
                if(dFiles.length > 0){
                    arrayValidacion.push(1,'');
                }else{
                    arrayValidacion.push(0,'Debe adjuntar un documento de justificación');
                }
            }
        }
        else{
            arrayValidacion.push(0,'Debe Seleccionar un medio de justificación');
        }
        return arrayValidacion;

    }
    //-----------
    function validarRazon() {

        var arrayValidacion = [];
        if( $("#saveSolicitud input[name='customRadio']:radio").is(':checked'))
        {
            let campo = $('input:radio[name=customRadio]:checked').attr('id');
            let index = campo.replace('razon','');
            let valorOpcionalTxt= $("#"+campo).data('opcionaltxt');
            if(valorOpcionalTxt == 1){
                if( $("#txtOpcional"+index).val() == ""){
                    arrayValidacion.push(0,'Debe Ingresar medio de Justificación Otros, un valor valido');
                    $('input:radio[name=customRadio]:checked').val('');
                }else{
                    arrayValidacion.push(1,'');
                }
            }else{
                arrayValidacion.push(1,'');
            }
        }
        else{
            arrayValidacion.push(0,'Debe Seleccionar un medio de justificación');
        }
        return arrayValidacion;
    }
    //-----------
    function validarPaso3() {

        var validacion = 1;
        var seleccion = $("#reportes").val();
        if( seleccion == -99){
            validacion = 0;
        }
        return validacion;

    }
    //----------
    function mostrarReporte() {

        $('#detalleReporte').attr('style','display: none');
        let seleccion = $('#reportes').val();
        let valorDescripcionReporte = $("#reportes option:selected").attr('data-nombreReporte');
        let textoDetalleReporte = $("#reportes option:selected").attr('data-textoDetalle');
        let urlReporte = $("#reportes option:selected").attr('data-url');
        if(seleccion != -99 ) {
            let codigoHtml = '<div class="alert alert-success" role="alert"><h4 class="alert-heading">'+valorDescripcionReporte+'</h4>';
            codigoHtml += '<p>'+textoDetalleReporte+'</p><hr><p class="mb-0">';
            codigoHtml += '<a href='+_URL+urlReporte+' target="_blank">Ver Reporte Ejemplo</a>';
            codigoHtml += '</p></div>';
            $('#detalleReporte').attr('style', '');
            $('#detalleReporte').html(codigoHtml);
        }

    }

    function cargaLabelResumen(){

        let txtRazon;
        let campoSeleccionRazon = $('input:radio[name=customRadio]:checked').attr('id');
        let indice = campoSeleccionRazon.replace('razon','');
        let valorDescripcionRazon = $("#"+campoSeleccionRazon).data('descripcion');
        let valorDescripcionReporte = $("#reportes option:selected").attr('data-nombreReporte');
        //busco cual es seleccionado
        $("input[id^='razon']").each(function( index,element ) {
            if( $("#txtOpcional"+(index+1)).length > 0){
                if( indice = (index+1)){
                    txtRazon = valorDescripcionRazon +' '+ $("#txtOpcional"+(index+1)).val();
                }
            }else{
                txtRazon = valorDescripcionRazon;
            }
        });
        $('#labelRazon').html(txtRazon);
        var run = $("#runConsulta").val();
        var runconsulta = run.replaceAll(".","");
        let runconsultasindv =  runconsulta.toString().split("-");
        var nombreConsulta = 'Consulta_'+runconsultasindv[0]+'_'+Date.now();
        $('#nombreConsulta').val(nombreConsulta);
        $('#labelConsulta').html(nombreConsulta);
        for (let i = 0, l = dFiles.length; i < l; i++) {
            $('#labelDocumento').append('<strong>'+dFiles[i].name+'</strong><br>');
        }
        $('#labelReporte').html(valorDescripcionReporte);
    }

    $( "#limpiarRun" ).click(function() {
        $("#runConsulta").val('');
    });

    $( "#consultarBoton" ).click(function() {

        var run =  $("#runConsulta").val();

        if(run.length > 12){
            Swal.fire({
                icon: 'info',
                text: 'El run supera la cantidad de digitos permitidos !',
                confirmButtonText: `Ok`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#runConsulta").val('');
                }
            })
        }else {
            var runconsulta = run.replaceAll(".", "");
            let runconsultasindv = runconsulta.toString().split("-");
            let url = _URL + 'consultar_rut';
            let loading;
            let datos = {"_token": "{{ csrf_token() }}", "rut": runconsultasindv[0]};
            $.ajax({
                data: datos,
                url: url,
                type: 'POST',
                beforeSend: function () {
                    loading = Swal.fire({
                        title: "Espere por favor...",
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        onOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (response) {

                    loading.close();
                    if (response.encontrado) {
                        loading = Swal.fire({
                            icon: 'success',
                            text: 'Consulta encontrada en RSH!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        //deshabilitar boton buscar y campo run
                        $('#consultarBoton').attr('disabled', 'disabled');
                        $('#runConsulta').attr('disabled', true);
                        //desplegar paso 2
                        $("#paso2").collapse('show')
                    } else {
                        loading = Swal.fire({
                            icon: 'error',
                            text: 'Consulta no encontrada en RSH!',
                        });
                        $("#runConsulta").val("");
                    }
                },
                error: function (error) {
                    loading.close();
                    loading = Swal.fire("Error en la consulta");
                    $("#runConsulta").val("");
                }
            });
        }
    });

    $( "#generarSolicitud").click(function (){
        //$("#declaracion").modal("show");
        const listMensajeItem = document.createElement('li');
        const listMensaje = document.createElement('ul');
        listMensajeItem.innerHTML = null;
        listMensajeItem.innerHTML +=
            '<p align="justify">Declaro estar en conocimiento:</p>';
        listMensajeItem.innerHTML +=
            '<div id="ingreso-checkbox" onchange="validacionConfirmacion()" onclick="pintarCheckboxesSwal()">' +
            '<label  id="textoConfirmo" for="swal-input1" class="swal-input-btn mb-3">' +
            '<div class="swal-checkbox-wrapper"><input type="checkbox"  id="swal-input1" class="swal-custom-checkbox" required></div>' +
            'De la prohibición de ceder bajo cualquier circunstancia mi clave y nombre de usuario para acceder a esta plataforma, y de la responsabilidad de las acciones efectuadas bajo el uso de mi clave y nombre.</label></div>' +
            '<div id="ingreso-checkbox" onchange="validacionConfirmacion()" onclick="pintarCheckboxesSwal()">' +
            '<label for="swal-input2" id="textoAutorizo" class="swal-input-btn" required>' +
            '<div class="swal-checkbox-wrapper"><input type="checkbox" id="swal-input2" class="swal-custom-checkbox" required></div>' +
            'De lo establecido en el artículo 10 de la Ley N° 20.520, sobre el deber de guardar reserva y secreto absolutos de la información que contenga datos personales de la cual tomo conocimiento en el cumplimiento de mis labores. Asimismo, del deber de abstención de usar dicha información en beneficio propio o de terceros. Para efectos de lo dispuesto en el inciso segundo del artículo 125 del Estatuto Administrativo se estimará que los hechos que configuren infracciones a este artículo vulneran gravemente el principio de probidad administrativa, sin perjuicio de las demás sanciones y responsabilidades que procedan.</label></div>';
        listMensaje.appendChild(listMensajeItem);
        Swal.fire
        ({
            type: 'warning',
            title:"ATENCIÓN",
            allowOutsideClick: false,
            allowEscapeKey: false,
            showCancelButton: true,
            width: '750px',
            showConfirmButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonText: "Continuar",
            html: listMensaje,
            reverseButtons:true,
            preConfirm: (value) => {
                return validacionConfirmacion();
            },

        }).then((result) => {
            if (result.value) {
                save();
            } else {

            }
        });
    });

    $( "#volverPaso1" ).click(function() {

        $("#runConsulta").val("");
        $('#consultarBoton').removeAttr('disabled');
        $("#paso2").collapse('hide')
        $('#input:radio[name=customRadio]').removeAttr("checked");

    });

    $( "#continuarPaso2" ).click(function() {

        var valid = validarPaso2();
        console.log("valor a validar = : " + valid[0]);
        if(valid[0] ==  1) {
            //deshabilitar razon
            $("input[id^='razon']").each(function( index,element ) {
                $("#razon"+(index+1)).attr('disabled',true);
            });
            $('#continuarPaso2').attr('disabled', 'disabled');
            $('#volverPaso1').attr('disabled', 'disabled');
            //
            for (let i = 0, l = dFiles.length; i < l; i++) {
                $('#elemBorrar'+i).addClass('disabled');
            }
            //
            $("#paso3").collapse('show')
        }
        else{
            Swal.fire({
                icon: 'error',
                text: valid[1],
            });
        }

    });

    $( "#volverPaso2" ).click(function() {

        $('#continuarPaso2').removeAttr('disabled');
        $('#volverPaso1').removeAttr('disabled');
        $("#paso3").collapse('hide')
        $('#reportes').val('-99');
        $('#Reporte1').hide();
        $('#Reporte2').hide();
        $('#Reporte3').hide();

    });

    $( "#continuarPaso3" ).click(function() {

        var valid = validarPaso3();
        if(valid == 1) {
            //deshabilitar select de Reporte
            $("#reportes").attr('disabled', true);
            //deshabilitar botones
            $('#continuarPaso3').attr('disabled', 'disabled');
            $('#volverPaso2').attr('disabled', 'disabled');
            //despliega paso 4
            $("#paso4").collapse('show')
            cargaLabelResumen();
        }else{
            Swal.fire({
                icon: 'error',
                text: 'Debe seleccionar un reporte para continuar',
            });
        }

    });

    $( "#volverPaso3" ).click(function() {

        $('#continuarPaso3').removeAttr('disabled');
        $('#volverPaso2').removeAttr('disabled');
        $("#paso4").collapse('hide')

    });

    function pintarCheckboxesSwal() {
        let seleccionarCheckbox = document.getElementsByClassName('swal-custom-checkbox');
        for (i = 0; i < seleccionarCheckbox.length; i++) {
            if(seleccionarCheckbox[i].checked) {
                seleccionarCheckbox[i].parentNode.parentNode.classList.add("checked");
            } else {
                seleccionarCheckbox[i].parentNode.parentNode.classList.remove("checked");
            }
        }
    }

    function validacionConfirmacion() {
        let check_declaracion = $('#swal-input1').prop('checked');
        let check_autorizacion = $('#swal-input2').prop('checked');
        validacion =(check_declaracion && check_autorizacion);
        if(validacion){
            $(".swal2-confirm").prop('disabled', false);
        }else{
            $(".swal2-confirm").prop('disabled', true);
        }

        return validacion;
    }

</script>
@include('transparencia.include.consulta_run_modals')
@stop
