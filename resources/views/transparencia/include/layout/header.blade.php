<header class="header">
    <nav class="navbar navbar-expand-md navbar-light bg-white">
        <div class="container">
{{--            <a class="navbar-brand pt-0" href="http://rshtransparencia.mideplan.cl">--}}
                <img id="logo" src="{{asset('assets/images/logo_cse-rsh.png')}}" class="img-fluid" alt="logo">
{{--            </a>--}}
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item ">
                        <a class="nav-link" href="{{asset("/escritorio")}}">
                            <i class="mdi mdi-home mdi-18px"></i>
                            Inicio
                            <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle"
                               href="#"
                               role="button"
                               id="userDropdown"
                               data-toggle="dropdown"
                               aria-haspopup="true"
                               aria-expanded="false">
                                <i class="mdi mdi-account-circle mdi-18px"></i>
                                    {{ Session::get('heskUserNombre')}}

                            </a>
                            <div class="dropdown-menu" aria-labelledby="userDropdown">
                                <h6 class="dropdown-header">Mantenedores</h6>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{asset("/mantenedor_usuario")}}">Mantenedor de Usuarios</a>
{{--                                <a class="dropdown-item" href="{{asset("/mantenedor_perfil")}}">Mantenedor de Perfiles</a>--}}
                            </div>
                        </div>

                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{asset("/salir")}}" tabindex="-1" aria-disabled="true">
                            <i class="mdi mdi-close-circle mdi-18px"></i>
                            Cerrar sesión
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
