<!DOCTYPE html>
<html lang="es">
<head>
    <title>SIAD</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" type="image/icon" href="{{asset('img/icon.png')}}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('theme/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{asset('theme/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('theme/dist/css/adminlte.min.css')}}">

    <style>
        .input-error {
            font-size: 12px;
            color: #dc3545;
            text-align: left;
            line-height: 14px;
            vertical-align: top;
        }
        .fondo-content {
            background-image: url({!! asset('img/content.jpg') !!}) !important;
            background-repeat: repeat;
        }
        .border-error {
            border-color: #dc3545;
        }
        @media(max-width: 991px) {
            .foto {
                display: none;
            }
        }
    </style>
    @yield('css')

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
</head>

<body class="hold-transition login-page fondo-content">
    <div class="login-box" style="width: auto;">
        @yield('content')
    </div>

    <!-- jQuery -->
    <script src="{{asset('theme/plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('theme/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('theme/dist/js/adminlte.min.js')}}"></script>
</body>
</html>
