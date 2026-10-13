<!DOCTYPE html>
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

{{ \App\Http\Controllers\Utiles::scriptAutoVersion("assets/js/jquery-3.5.1.min.js") }}
{{ \App\Http\Controllers\Utiles::scriptAutoVersion("assets/js/jquery.Rut.js") }}
{{ \App\Http\Controllers\Utiles::scriptAutoVersion("assets/js/main.js") }}

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <title>Ministerio de Desarrollo Social y Familia - Gobierno de Chile</title>
</head>
<body>
<div id="wrapper">
    <div id="page-wrapper2">
        <header>
            <div class="bicolor"><span class="blue"></span><span class="red"></span></div>
            <div class="col-lg-12 col-md-8 col-xs-12">
                <h2 class="blue">
                    <strong>Plataforma Transparencia
                        @if(isset($_SERVER["HTTP_HOST"]) && $_SERVER["HTTP_HOST"] == 'rshmunicipalcap.ministeriodesarrollosocial.gob.cl')
                            <span style="float: right; font-size:1.8em;"> CAPACITACIÓN </span>
                        @endif
                    </strong>
                </h2>
            </div>
        </header>
        <section id="content">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Login</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="alert alert-info">
                        <ul>
                            <li>Recuerde que sus credenciales de acceso son personales e intransferibles.</li>
                        </ul>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger" id="" role="alert"><ul><li>{{$errors->first()}}</li></ul></div>
                @endif
                </div>
                {{--<div class="col-md-1"></div>--}}
                <!--login-->
                <div id="loginbox" class="mainbox col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">Ingreso a la Plataforma Transparencia</div>
                        </div>
                        <div class="panel-body">
                            @if (@$alerta)
                                <div class="alert alert-warning">{{$alerta}}</div>
                            @endif
                            @if (@$message)
                                <div class="alert alert-success">{{$message}}</div>
                            @endif

                            <form id="loginform" class="form-horizontal" role="form" action="{{asset("/ingresar")}}"
                                  method="post">
                                @csrf
                                <div style="margin-bottom: 25px" class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <input id="login-username" type="text" maxlength="12"
                                           class="form-control" name="run" placeholder="RUN" required value=""/>
                                </div>

                                <div style="margin-bottom: 25px" class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input id="login-password" type="password" class="form-control" name="password"
                                           placeholder="Contraseña" required value=""/>
                                </div>
                                <div style="margin-top:10px" class="form-group">
                                    <div class="col-sm-12 controls">
                                        <button class="btn btn-primary"
                                                data-callback='onSubmitloginform'
                                                data-action='submit'>Acceder</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </section>

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
                <div class="left">
                    <span> Ministerio de Desarrollo Social y Familia - Gobierno de Chile - Catedral 1575, Santiago - Teléfono 800 104 777</span>
                </div>
                <nav>
                    <ul>
                        <li><a href="http://www.registrosocial.gob.cl/politica-de-privacidad" target="_blank">
                                Políticas de Privacidad</a>
                        </li>
                    </ul>
                </nav>
                <div class="cf"></div>
                <div class="logo"><a href="#" target="_blank"><span>Ministerio de Desarrollo Social y Familia</span></a>
                </div>
            </div>
        </div>
    </footer>
    <!-- #end footer area -->
    </div>
</div>

</body>
</html>
