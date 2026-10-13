jQuery(function ($) {
    $('#login-username').soloCaracteresRUN();
    $('#login-username').Rut({format_on: 'keyup'}); // run del modal inicial
    $('#login-username').pasteCaracterRun();
    $('#runConsulta').soloCaracteresRUN();
    $('#runConsulta').Rut({format_on: 'keyup'});
    $('#runConsulta').pasteCaracterRun();
});

jQuery.fn.soloCaracteresRUN = function () {
    $(this).keypress(function (e) {
        var char_code = (e.which) ? e.which : e.keyCode;
        if (char_code > 31 && (char_code < 48 || 57 < char_code) && (char_code != 75) && (char_code != 107)) { // caracteres que no son traídos
//      (mayores que 31) y ( (menores que 48) o (mayores que 57) ) y distinto a 75
            return false;
        }
    });
};


jQuery.fn.pasteCaracterRun = function () {
    $(this).on('paste', function () {
        var element = this;
        setTimeout(function () {
            var valor = $(element).val().replace(/\s+/g, '')
                .replace(/[^0-9kK]/g,"")//(/\D\K/g, "")
                .replace(/([0-9])([0-9]|k|K{1})$/, '$1-$2')
                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
            $(element).val(valor);
        }, 100);
    });
};

function getFile(idSol, idDoc, fileName) {
    let url = idDoc == '' ? _URL + 'descargaAdjuntos/' + idSol : _URL + 'descargaAdjuntos/' + idSol + '/' + idDoc;
    $.ajax({
        method: 'GET',
        url: url,
        cache: false,
        xhr: function () {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 2) {
                    if (xhr.status == 200) {
                        xhr.responseType = "blob";
                    } else {
                        xhr.responseType = "text";
                    }
                }
            };
            return xhr;
        },
        success: function (data) {
            //Convert the Byte Data to BLOB object.
            var blob = new Blob([data], {type: "application/octetstream"});

            //Check the Browser type and download the File.
            var isIE = false || !!document.documentMode;
            if (isIE) {
                window.navigator.msSaveBlob(blob, fileName);
            } else {
                var url = window.URL || window.webkitURL;
                link = url.createObjectURL(blob);
                var a = $("<a />");
                a.attr("download", fileName);
                a.attr("href", link);
                $("body").append(a);
                a[0].click();
                $("body").remove(a);
            }
        },
        error: function (data) {
            Swal.fire('error',
                data.responseJSON.data,
                'error');
        }
    });
}

function getReporte(idSol, fileName) {
    let url = _URL + 'descargaGenerado/' + idSol;
    $.ajax({
        method: 'GET',
        url: url,
        cache: false,
        xhr: function () {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 2) {
                    if (xhr.status == 200) {
                        xhr.responseType = "blob";
                    } else {
                        xhr.responseType = "text";
                    }
                }
            };
            return xhr;
        },
        success: function (data) {
            //Convert the Byte Data to BLOB object.
            var blob = new Blob([data], {type: "application/octetstream"});

            //Check the Browser type and download the File.
            var isIE = false || !!document.documentMode;
            if (isIE) {
                window.navigator.msSaveBlob(blob, fileName);
            } else {
                var url = window.URL || window.webkitURL;
                link = url.createObjectURL(blob);
                var a = $("<a />");
                a.attr("download", fileName);
                a.attr("href", link);
                $("body").append(a);
                a[0].click();
                $("body").remove(a);
            }
        },
        error: function (data) {
            Swal.fire('error',
                data.responseJSON.data,
                'error');
        }
    });
}

function emailSolicitud(idSol) {
    let url = _URL + 'emailGenerado/' + idSol;
    $.ajax({
        method: 'GET',
        url: url,
        cache: false,
        success: function (data) {
            Swal.fire('Exito',
                data,
                'success');
        },
        error: function (data) {
            Swal.fire('error',
                data,
                'error');
        }
    });
}

function NumText(string){//solo letras y numeros
    var out = '';
    //Se añaden las letras validas
    var filtro = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890._-';//Caracteres validos

    for (var i=0; i<string.length; i++)
        if (filtro.indexOf(string.charAt(i)) != -1)
            out += string.charAt(i);
    return out;
}
