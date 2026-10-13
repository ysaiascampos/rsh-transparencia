<div class="row">
    <div class="col-12">

        <h5>Seleccione medio de justificación para la consulta de datos</h5>
        <div class="form-group">
            @foreach($lstRazones as $index => $razon)
                <div class="custom-control custom-radio">
                    <input type="radio" id="razon{{$index+1}}" name="customRadio"
                           class="custom-control-input" value="{{$razon->id_razon}}" data-descripcion="{{$razon->descripcion_razon}}"
                           data-opcionaltxt="{{$razon->texto_opcional}}" onclick="habilitar(this)">
                    <label class="custom-control-label" for="razon{{$index+1}}" >{{$razon->descripcion_razon}}</label>
                    @if($razon->texto_opcional == 1)
                        <input type="text" name="txtOpcional{{$index+1}}" id="txtOpcional{{$index+1}}" disabled="disabled"/>
                    @endif
                </div>
            @endforeach
        </div>

    </div>
    <br>
    <div class="col-12">
        <h5>Adjunte justificación</h5>
    </div>
    <div class="col-12 col-md-6 pb-2">
        <div class="form-group container js " id="attachment" name="attachment">
            <div class="box has-advanced-upload">
                <div>
                    <div class="box__input">
                        <svg class="box__icon" xmlns="http://www.w3.org/2000/svg" width="50" height="43" viewBox="0 -30 50 80"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"></path></svg>
                        <input type="file" name="files[]" id="file" class="box__file" data-multiple-caption="{count} files selected" multiple="" accept="image/jpg, image/jpeg, image/gif, image/png, application/pdf, .csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        <label id="fileLabel" for="file"><strong>Escoge un archivo, o arrástralo Aqui.</strong></label>
                        <button type="submit" class="box__button">Upload</button>
                    </div>
                    <div class="box__uploading">Uploading…</div>
                    <div class="box__success">Done! <a href="https://css-tricks.com/examples/DragAndDropFileUploading//?" class="box__restart" role="button">Upload more?</a></div>
                    <div class="box__error">Error! <span></span>. <a href="https://css-tricks.com/examples/DragAndDropFileUploading//?" class="box__restart" role="button">Try again!</a></div>
                    <input type="hidden" name="ajax" value="1">
                </div>
            </div>
            <div id="imgPreview"></div>
        </div>

        <button type="button" id="continuarPaso2"
                class="btn btn-primary col-md-3 my-3 w-100">Continuar</button>
        <!--<button type="button" id="volverPaso1" style="margin-left: 20px"
                class="btn btn-info col-md-4 my-4 w-100">Volver Paso 1</button>-->
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
