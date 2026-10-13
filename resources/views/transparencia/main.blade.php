<!doctype html>
<html lang="es">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{ \App\Http\Controllers\Utiles::scriptAutoVersion("assets/js/jquery-3.5.1.min.js") }}
    {{ \App\Http\Controllers\Utiles::scriptAutoVersion("assets/js/jquery.Rut.js") }}
    {{ \App\Http\Controllers\Utiles::scriptAutoVersion("assets/js/main.js") }}
    {{ \App\Http\Controllers\Utiles::scriptAutoVersion("assets/js/datatables.min.js") }}
    {{ HTML::script('assets/js/bootstrap.bundle.min.js') }}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        var _URL = '{{asset('')}}';
    </script>
    <!-- Bootstrap CSS -->
    {{ HTML::style('/assets/css/bootstrap.min.css')  }}
    <!-- Material Design Icons -->
    {{ HTML::style('/assets/fonts/mdi/css/materialdesignicons.css')  }}
    <!-- Custom CSS -->
    {{ HTML::style('/assets/css/main.css')  }}

    {{ HTML::style('assets/css/datatables.min.css') }}

    <title>Transparencia y Reportería RSH - CSE, Ministerio de Desarrollo Social y Familia</title>
</head>
<body class="bg-body">
@include('transparencia.include.layout.header')
<main>
    @section('content')
    @show
</main>
@include('transparencia.include.layout.footer')

<!-- Custom JavaScript -->
{{ HTML::script('assets/js/custom.js') }}

</body>
</html>
