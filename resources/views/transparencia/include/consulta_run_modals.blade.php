<!-- Modal archivo justificacion-->
<div class="modal fade" id="error" tabindex="-1" aria-labelledby="error" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="error">Documento de justificación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body"><span class="text-danger">No se adjuntado archivo de justificación de
          solicitud del dato, favor adjuntar para continuar</span></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Adjuntar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal disclaimer-->
<div class="modal fade" id="declaracion" tabindex="-1" aria-labelledby="declaracion" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="declaracion">Declaración jurada</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p style="text-align:justify; margin-bottom:20px;">
                Declaro que la información proporcionada es fidedigna, integra y correcta, y autorizo al
                Ministerio de Desarrollo Social y Familia para que la utilice, verifique y/o complemente
                administrativamente, de conformidad con la información con que cuenta, con la que puedan
                proveerle otros organismos públicos, con los datos que se consignen y con todos aquellos
                necesarios para efectos de su caracterización socioeconómica. Asimismo, autorizo al Ministerio
                el tratamiento de toda esta información de conformidad con lo dispuesto en el Decreto Supremo N°
                22, de 2015, del Ministerio de Desarrollo Social y Familia, en la Ley N° 19.628, sobre Protección de la
                Vida Privada, y en las demás normas aplicables.
                </p>
                <input type="checkbox" aria-label="Checkbox for following text input" class="pr-2"> Acepto Terminos
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                <p class="mb-0">
                    <button type="button" class="btn btn-success" onclick="save();" data-dismiss="modal" >Aceptar</button>
                </p>
            </div>
        </div>
    </div>
</div>

