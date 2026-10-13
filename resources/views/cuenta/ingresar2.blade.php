<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
  <title>Ministerio de Desarrollo Social y Familia - Gobierno de Chile</title>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
      <script src='https://www.google.com/recaptcha/api.js' type="text/javascript" ></script>
    </head>

    <body>
      <div id="wrapper">
        <div id="page-wrapper2">
          <header>
            <div class="bicolor"><span class="blue"></span><span class="red"></span></div>
            <div class="col-lg-12 col-md-8 col-xs-12">
              <h2 class="blue"><strong>Plataforma Transparencia @if(isset($_SERVER["HTTP_HOST"]) && $_SERVER["HTTP_HOST"] == 'rshmunicipalcap.ministeriodesarrollosocial.gob.cl')<span style="float: right; font-size:1.8em;"> CAPACITACIÓN </span>@endif  </strong></h2>

            </div>

          </header>
          <section id="content">
            <div class="row">
              <div class="col-lg-12">
                <h3 class="page-header text-center">@if (@$alerta) {{$alerta}}  @endif </h3>
              </div>        <!-- /.col-lg-12 -->
            </div>
            <div class="row">
              <div class="col-md-12" align="center">
                  @if (env('VIA_TOKEN_SSO'))
                <a href="https://sso.ministeriodesarrollosocial.gob.cl/" type="button" class="btn btn-primary" style="width:150px;"><strong>Volver</strong></a>
                  @else
                <a href="{{asset('')}}" type="button" class="btn btn-primary" style="width:150px;"><strong>Volver</strong></a>
                  @endif
              </div>
            </div>
          </section>
        </div>
        <!-- footer area -->
        <footer>
          <div class="wrapper clearfix">
            <div class="bicolor"><span class="blue"></span><span class="red"></span></div>
            <div class="top">
              <div class="listas"></div>
              <div class="cf"></div>
              <div class="sep"></div>
            </div>
            <div class="bottom">
              <div class="left"> <span> Ministerio de Desarrollo Social y Familia -
              Gobierno de Chile - Catedral 1575, Santiago - Teléfono 800 104 777</span> </div>
              <nav>
                <ul>
                  <li><a href="http://www.registrosocial.gob.cl/politica-de-privacidad/" target="_blank">Políticas de Privacidad</a></li>
                </ul>
              </nav>
              <div class="cf"></div>

            </div>
          </div>
        </footer>
        <!-- #end footer area -->
      </div>

    </body>
    </html>
