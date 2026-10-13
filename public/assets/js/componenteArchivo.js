//----------Manejo de archivos
var idattached = 2;
let $form = $('.box');
let droppedFiles = false;
var dFiles = [];

$(document).ready( function() {

    $('#file').on( 'change', function( e ) {
        loadFiles( e.target.files );
    });

    $form.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
    })
        .on('dragover dragenter', function() {
            $form.addClass('is-dragover');
        })
        .on('dragleave dragend drop', function() {
            $form.removeClass('is-dragover');
        })
      /*  .on('drop', function(e) {
            loadFiles(e.originalEvent.dataTransfer.files);
        });*/
});
//-----------------------
function loadFiles (files) {

    let valid_types = ["image/jpg", "image/jpeg", "image/gif", "image/png", "application/pdf", ".csv", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/vnd.ms-excel"];
    let valid = validarRazon();
    if(valid[0] ==  1) {
        //obtener razon seleccionada y opcional texto
        let campo = $('input:radio[name=customRadio]:checked').attr('id');
        let index = campo.replace('razon','');
        let idRazon = $("#"+campo).val();
        let valorOpcionalTxt= $("#"+campo).data('opcionaltxt');
        let valorDecripcionRazon= $("#"+campo).data('descripcion');
        let txtRazon='';
        let superaMax = 0;
        if(valorOpcionalTxt == 1) {
            txtRazon = $("#txtOpcional" + index).val();
        }
        for (let i = 0, l = files.length; i < l; i++) {
            if (valid_types.includes(files[i].type) === true) {
                if( files[i].size < 2097152 ) {
                    files[i]['idRazon'] = idRazon;
                    files[i]['txtRazon'] = txtRazon;
                    files[i]['descripcionRazon'] = valorDecripcionRazon;
                    dFiles.push(files[i]);
                }else {
                    superaMax = 1;
                }
            }
        }
        if( superaMax ){
            Swal.fire({
                icon: 'error',
                text: "se supera el maximo permitido , 2 MB !",
            });
        }else {
            loadPreviews();
            desactivarRazones();
        }
    }
    else{
        Swal.fire({
            icon: 'error',
            text: valid[1],
        });
    }
}
//-----------------------
function loadPreviews() {

    let divPreview = $('#imgPreview');
    divPreview.html("<ul name='listaFiles' id='listaFiles' class='list-group list-group-flush'></ul>");
    if (dFiles.length > 0) {
        for (let i = 0, l = dFiles.length; i < l; i++) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $("#listaFiles").append('<li class="list-group-item px-0"><i class="mdi mdi-paperclip">'+dFiles[i].name+ ' ( '+dFiles[i].descripcionRazon+' ) '+'</i>&nbsp;<a name="elemBorrar'+i+'" id="elemBorrar'+i+'" data-id="'+i+'" onclick="borrarArchivo(this)" href="#"><i class="mdi mdi-delete"></i></a></li>');
            }
            reader.readAsDataURL(dFiles[i]); // convert to base64 string
        }
    }
}
//-----------------------
function borrarArchivo(obj){

    let idBorrar = obj.dataset.id;
    let banBorrado = $('#elemBorrar'+idBorrar).hasClass('disabled');
    if(!banBorrado) {
        dFiles.splice(idBorrar, 1);
        //preguntar si no hay mas archivos habilita bloqueo de razon
        if (dFiles.length == 0) {
            habilitaRazones();
        }
        loadPreviews();
    }
}
