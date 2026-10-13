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
                title: "Espere por favor...",
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
                    text: 'Se presento un error al guardar!',
                })
            }
        },
        error: function (error) {
            loading.close();
            loading = Swal.fire({
                icon: 'error',
                text: 'No se pudo Generar la Solicitud!',
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
    if( $("#saveSolicitud input[name='customRadio']:radio").is(':checked'))
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


