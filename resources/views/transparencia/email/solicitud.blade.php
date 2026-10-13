<!DOCTYPE html>
<html lang="es-CL">
<head>
    <meta charset="utf-8">
    <style>
        html, body {
            font-family:Calibri,"Segoe UI","Helvetica Neue",Helvetica,sans-serif;
        }
        code {
            font-family: Menlo,Monaco,Consolas,"Courier New",monospace;
            color: #c7254e;
        }
    </style>
</head>
<body>
<h2>Transparencia y Reportería RSH - CSE, Ministerio de Desarrollo Social y Familia</h2>
<div>

    Estimado/a <br/>
    Reciba un cordial saludo del Ministerio de Desarrollo Social y Familia.<br /><br />
    De acuerdo a nuestros registros, usted realizó la siguiente solicitud:<br /><br />

    <table border="1" width="100%" style="border-collapse: collapse">
        <tr>
            <td width="20%"><strong>Nro. solicitud</strong></td>
            <td width=""><strong>Tipo Reporte</strong></td>
            <td width=""><strong>Realizada el día</strong></td>
        </tr>
        <tr>
            <td>{{ $id }}</td>
            <td>{{ $reportes[$tipo] }}</td>
            <td>{{ $fecha }}</td>
        </tr>
    </table>
    <br/>

    Adjunto en este correo usted podrá descargar el Reporte solicitado. <br/><br/>

    <small>Saludos cordiales,</small><br/><br/>
    <small>Ministerio de Desarrollo Social y Familia</small><br/><br/>
    <small>NOTA: Le pedimos no responder este correo que es solo de carácter informativo.</small>

</div>
</body>
</html>
