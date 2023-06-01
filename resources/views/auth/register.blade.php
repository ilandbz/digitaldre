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

</head>

<body class="hold-transition login-page fondo-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center" style="background-color: #f8f9fa;">
                        <h2>REGISTRO</h2>
                        <img src="{{asset('img/logo_este.png')}}" alt="" class="img-fluid">
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('registerpublico') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="c_dni" class="col-md-4 col-form-label text-md-end">{{ __('DNI') }}</label>
    
                                <div class="col-md-6">
                                    <input id="c_dni" type="text" class="form-control @error('c_dni') is-invalid @enderror" name="c_dni" value="{{ old('c_dni') }}" required autocomplete="Apellidos y Nombres" autofocus>
    
                                    @error('c_dni')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>  
                            <div class="row mb-3">
                                <label for="x_nombre" class="col-md-4 col-form-label text-md-end">{{ __('Apellidos y Nombres') }}</label>
    
                                <div class="col-md-6">
                                    <input id="x_nombre" type="text" class="form-control @error('x_nombre') is-invalid @enderror" name="x_nombre" value="{{ old('x_nombre') }}" required autocomplete="Apellidos y Nombres" autofocus>
    
                                    @error('x_nombre')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>    
    
                            <div class="row mb-3">
                                <label for="x_email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
    
                                <div class="col-md-6">
                                    <input id="x_email" type="email" class="form-control @error('x_email') is-invalid @enderror" name="x_email" value="{{ old('x_email') }}" required autocomplete="email">
    
                                    @error('x_email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="x_telefono" class="col-md-4 col-form-label text-md-end">{{ __('TELEFONO') }}</label>
    
                                <div class="col-md-6">
                                    <input id="x_telefono" type="text" class="form-control @error('x_telefono') is-invalid @enderror" name="x_telefono" value="{{ old('x_telefono') }}" required autocomplete="Apellidos y Nombres" autofocus>
    
                                    @error('x_telefono')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div> 
                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
    
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
    
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
    
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                                @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>
    
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{asset('theme/plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('theme/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('theme/dist/js/adminlte.min.js')}}"></script>
</body>
</html>

