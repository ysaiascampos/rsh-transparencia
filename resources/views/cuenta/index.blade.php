<!doctype html>
<html lang="es">
<!-- Bootstrap Core CSS -->
<!-- Bootstrap CSS -->
{{ HTML::style('/assets/css/bootstrap.min.css')  }}
<!-- Material Design Icons -->
{{ HTML::style('/assets/fonts/mdi/css/materialdesignicons.css')  }}
<!-- Custom CSS -->
{{ HTML::style('/assets/css/main.css')  }}
<!-- Custom CSS -->
{{ HTML::style('/assets/css/admin.css') }}

<!-- Custom Fonts -->
{{ HTML::style( '/assets/fonts/fontawesome-5.15.1/css/all.min.css')  }}

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <title>Transparencia y Reportería RSH - CSE, Ministerio de Desarrollo Social y Familia</title>
</head>

<body>
<div id="wrapper">
    <div id="page-wrapper2">
        <header>
            <div class="bicolor"><span class="blue"></span><span class="red"></span></div>
            <div class="col-lg-12 col-md-8 col-xs-12">
                <h2 class="blue">
                    <strong>Transparencia <br>y Reportería RSH - CSE
                        @if(isset($_SERVER["HTTP_HOST"]) && $_SERVER["HTTP_HOST"] == 'rshmunicipalcap.ministeriodesarrollosocial.gob.cl')
                            <span style="float: right; font-size:1.8em;"> CAPACITACIÓN </span>
                        @endif
                    </strong>
                </h2>
            </div>
        </header>
        <section id="content">
            <section class="banner-home">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-5 mb-4">
                            <h1 class="text-white mb-4">Consulta por reportes de la Plataforma de Registro Social de Hogares y
                                Calificación Socioeconómica</h1>
                            @if(env('ambienteDebug'))
                                <a class="btn btn-primary w-100 col-sm-3" href="{{ route('login') }}" role="button">Acceder</a>
                            @endif
                            <a class="btn btn-primary w-100 col-sm-3" href="{{ route('postlogin') }}" role="button">Acceder MDSF</a>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-12 col-md-6 mb-3"> <img src="{{asset('assets/images/imagen_central.png')}}" class="img-fluid"> </div>
                    </div>
                </div>
            </section>
        </section>
        <section class="banner">
            <div class="d-flex justify-content-center">
                <div class="row my-5 mx-1 text-center">
                    <div class="col-md-4 mb-3"><a href="http://www.registrosocial.gob.cl/" title="RSH" target="_blank"><img
                                src="./assets/images/banner_rsh.png" class="shadow img-fluid" alt="Registro social de hogares"></a>
                    </div>
                    <div class="col-md-4 mb-3"><a href="http://datasocial.ministeriodesarrollosocial.gob.cl/"
                                                  target="_blank"><img
                                src="./assets/images/banner_datasocial.png" alt="Data Social" class="shadow img-fluid"
                                title="Data Social"></a>
                    </div>
                    <div class="col-md-4 mb-3"><a href="https://adisv2.ministeriodesarrollosocial.gob.cl/" target="_blank"><img
                                src="./assets/images/banner_adis.png" alt="Adis" class="shadow img-fluid" title="Adis"></a></div>
                </div>
            </div>
        </section>
<!-- footer area -->
        <footer>
            <div class="container"><a href="http://www.desarrollosocialyfamilia.gob.cl/" target="_blank"><img
                        src="{{asset('assets/images/logo-mds-extend.svg')}}" class="img-fluid" alt="Ministerio de Desarrollo Social"
                        title="Ministerio de Desarrollo Social"></a></div>
        </footer>
    </div>
</div>
</body>

</html>
