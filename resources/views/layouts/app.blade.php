<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/icon" href="{{asset('img/icon.png')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>.:: SIAD ::.</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('theme/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{asset('theme/plugins/toastr/toastr.min.css')}}">
    <link rel="stylesheet" src="{{asset('theme/plugins/select2/css/select2.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('theme/dist/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/busyload.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/app2.css')}}">

    <style>
        .elevation-0 {
            box-shadow: 0 0.125rem 0.375rem 0 rgb(0 0 0 / 90%) !important;
            /*box-shadow: 0 0.125rem 0.375rem 0 rgb(161 172 1084 / 90%) !important;*/
        }
        .brand-link {
            border-bottom: 0px solid #06315c !important;
        }
        .nav-link .fa-circle {
            font-size: 8px !important;
            vertical-align: middle !important;
        }
        .nav-sidebar>.nav-item>.nav-treeview {
            background-color: #ffffff !important; /*cambia de color menu tablas*/
            margin-bottom: 20px;
        }
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active 
        {
          background-color: #ff9800; !important; /* color dseleccion boton menu*/
            color: rgb(255 255 255);  /* color del texton boton menu*/
        }
        .dropdown-item.active, .dropdown-item:active {
            color: #fff;
            text-decoration: none;
            background-color: #dc3545;
        }
        .dropdown-item:active span {
            color: #fff !important;
        }
        .disabled {
            cursor: no-drop;
            opacity: 0.4;
        }
        .logout {
            color: #35b5dc;
        }
        .dropdown-sm .dropdown-item {
            font-size: 15px;
            padding: 0px 1rem;
            line-height: 22px;
        }
        .activo {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
        }
        .grecaptcha-badge {
            bottom: 58px !important;
        }    
        .main-footer {
            background-color: #f5f5f9; /*COLOR DEL COPY RIGHT*/
            border-top: 0 solid #c1c1c1;
            color: #869099;
            padding: 1rem 1.7rem;
        }
        .fondo-content {
            background-image: url({!! asset('img/content.jpg') !!}) !important;
            background-repeat: repeat;
        }
        .navbar-dark {
            border-color: #f4f9f6;
        }
        .modal .btn-close {
            position: absolute;
            right: 13px;
            top: 17px;
            transform: translate(24px, -27px);
            background-color: #f90000;
            border-radius: 0.5rem;
            opacity: 1;
            padding: 6px 12px;
            box-shadow: 0 .125rem .25remrgba(161,172,184,.4);
            transition: all .23s ease .1s;
            border: 0;
            color: #fff;
        }
        .btn-close:hover{
            transform:translate(20px, -21px) !important;
        }
        .perfil-name {
            color: #000;
            opacity: 1;
            font-size: 18px;
            overflow: hidden;
            width: 250px;
            text-align: center;
            text-decoration: underline;
        }
        .sidebar-search-results .text-light {
            color: #000 !important;
        }
        .custom-control-label::before {
            top: 0.2rem;
            left: -1.59rem;
            width: 1.1rem;
            height: 1.1rem;
            background-color: #ffffff;
            border: #adb5bd solid 1px;
            box-shadow: 0 2px 4px 0 rgb(60 60 60 / 40%);
        }
        .nav-header {
            color: #a1acb8 !important;
        }
        .separador {
            content: "";
            position: absolute;
            left: -8px;
            margin-top: 7px;
            width: 1rem;
            height: 2px;
            transition: all .3s ease-in-out;
            background-color: #dc354557 !important;
        }
        .btn-rounded {
            border-radius: 50%;
        }
        .btn-rounded i {
            font-size: 15px;
        }
    </style>

    {{-- <script type="text/javascript">
        function callbackThen(response){
            // read HTTP status
            console.log(response.status);

            // read Promise object
            response.json().then(function(data){
                console.log(data);
            });
        }

        function callbackCatch(error){
            console.error('Error:', error)
        }

    </script>

    {!! htmlScriptTagJsApi([
        'callback_then' => 'callbackThen',
        'callback_catch' => 'callbackCatch'
    ]) !!} --}}
    
    @yield('css')
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{asset('img/rotate.png')}}" alt="CSJLE" height="100" width="100">
        </div>

        <!-- Navbar main-header navbar navbar-expand navbar-white navbar-light-->
        <nav class="main-header navbar navbar-expand navbar-dark navbar-dark"> <!-- COLOR DEL FOOTER SUPERIOS CERRAR CESION-->
            <!-- Left navbar links -->
            <ul class="navbar-nav pl-2">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <!-- <li class="nav-item d-none d-sm-inline-block">
                <a href="index3.html" class="nav-link">Home</a>
                </li> -->
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto pr-3">
                <!-- Navbar Search -->
                {{-- <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li> --}}

                <!-- Messages Dropdown Menu -->
                {{-- <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="{{asset('theme/dist/img/user1-128x128.jpg')}}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Brad Diesel
                                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">Call me whenever you can...</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="{{asset('theme/dist/img/user8-128x128.jpg')}}" alt="User Avatar" class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        John Pierce
                                        <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">I got your message bro</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="{{asset('theme/dist/img/user3-128x128.jpg')}}" alt="User Avatar" class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    Nora Silvester
                                    <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                                </h3>
                                <p class="text-sm">The subject goes here</p>
                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                    </div>
                </li> --}}

                <!-- Notifications Dropdown Menu -->
                {{-- <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li> --}}

                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link p-0" data-toggle="dropdown" href="#">
                        <i class="fas fa-user-circle" style="font-size: 40px;"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header disabled perfil-name">{{optional(Auth::user())->x_nombres ?? Session::get('c_dni')}}</span>
                        <div class="dropdown-divider"></div>
                        <a href="{{route('home')}}" class="dropdown-item">
                            <i class="fas fa-user-cog mr-2"></i> Datos de Perfil
                        </a>
                        <div class="dropdown-divider"></div>

                        <!-- /.logout -->
                        <a class="dropdown-item logout" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-power-off mr-2"></i> Cerrar Sesión
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar elevation-0 sidebar-light-primary">
            <!-- Brand Logo -->
            <a href="{{route('home')}}" class="brand-link form-inline">
                <img src="{{asset('img/rotate.png')}}" alt="CSJLE Logo" class="brand-image">
                <div class="brand-text font-weight-light" style="    white-space: normal; line-height: 15px; font-size: 18px;">DIRECCIÓN REGIONAL DE HUÁNUCO</div>
            </a>
            
            <!-- Sidebar -->
            <div class="sidebar">

                <!-- SidebarSearch Form -->
                <div class="form-inline mt-3">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar Menu -->
                @if (Session::has('c_dni'))
                    @include('layouts.menupublico')
                @else
                    @include('layouts.menu')
                @endif
                
            </div>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>OFIN-DRE: Version</b> 1.0
            </div>
            <strong>Copyright &copy; 2022 <a href="https://www.drehuanuco.gob.pe">SISTEMA DE ADMINISTRACIÓN DIGITAL</a>.</strong> Todos los derechos reservados.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{asset('theme/plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('theme/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('theme/dist/js/adminlte.min.js')}}"></script>
    <script src="{{asset('theme/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('theme/plugins/toastr/toastr.min.js')}}"></script>
    <script src="{{asset('js/busyload.min.js')}}"></script>
    <script src="{{asset('js/axios.min.js')}}"></script>
    <script src="{{asset('js/vue.min.js')}}"></script>
    {{-- <script src="{{asset('js/bootstrap.js')}}"></script> --}}
    <script>
        function Numero() {
            if (event.keyCode < 48 || event.keyCode > 57) {
                event.returnValue = false;
            }
        };
        function Digito() {
            if (event.keyCode < 46 || event.keyCode > 57) {
                event.returnValue = false;
            }
        };
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    @yield('js')
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="dist/js/demo.js"></script> -->
</body>
</html>