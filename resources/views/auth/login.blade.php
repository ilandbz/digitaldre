@extends('layouts.app_login')

@section('content')
<div class="row">
    <div class="col-lg-6 col-md-12 col-sm-12 foto">
        <img src="{{asset('img/pj_lima_este2.png')}}" alt="fondo" class="img-fluid">
        
    </div>
    <div class="col-lg-3 col-md-12 col-sm-12">
        <div class="card card-outline card-secondary" style="min-width: 370px;">
            <div class="card-header text-center" style="background-color: #f8f9fa;">
                <img src="{{asset('img/logo_este.png')}}" alt="" class="img-fluid">
            </div>
            <div class="card-body mt-1">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                <h5 class="login-box-msg mb-2">PUBLICO</h5>
                <form action="{{ route('loginpublico') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="hidden" name="tipoacceso" value="0">
                            <input id="c_dni" type="text" class="form-control @error('c_dni') is-invalid @enderror" name="c_dni" value="{{ old('c_dni') }}" required placeholder="Ingrese su DNI" autofocus>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        @error('c_dni')
                            <span class="input-error">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Ingrese su contraseña">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        @error('password')
                            <span class="input-error">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3 mt-3">
                            <a href="{{route('registered')}}">Registrarse</a>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-secondary btn-block">Iniciar Sesión</button>
                        </div>
                    </div>
                </form>
            
                <p class="mb-1">
                    {{-- <a href="#">¿Olvidó su contraseña?</a> --}}
                </p>
            </div>    
        </div>
    </div>
    <div class="col-lg-3 col-md-12 col-sm-12">
        <div class="card card-outline card-secondary" style="min-width: 370px;">
            <div class="card-header text-center" style="background-color: #f8f9fa;">
                <img src="{{asset('img/logo_este.png')}}" alt="" class="img-fluid">
            </div>
            <div class="card-body mt-1">
                <h5 class="login-box-msg mb-2">INTRANET</h5>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="hidden" name="tipoacceso" value="1">
                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" placeholder="Ingrese su usuario" autofocus>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        @error('username')
                            <span class="input-error">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Ingrese su contraseña">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        @error('password')
                            <span class="input-error">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3 mt-3">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember" style="font-weight: 400;">Recordarme</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-secondary btn-block">Iniciar Sesión</button>
                        </div>
                    </div>
                </form>
            
                <p class="mb-1">
                    {{-- <a href="#">¿Olvidó su contraseña?</a> --}}
                </p>
            </div>    
        </div>
    </div>
</div>
@endsection
