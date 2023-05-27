@extends('layouts.app')

@section('css')
    <style>
        .fz-30 {
            font-size: 30px;
        }
        .info-box .info-box-icon {
            width: 95px;
        }
        .info-box .info-box-icon {
            font-size: 2.5rem;
        }
    </style>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row" style="padding-top: 30px;" id="informacion">

                <!-- MODAL -->
                <div class="modal fade" id="formularioModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog" role="document" :class="modal.size">
                        {{-- DATOS --}}
                        <div class="modal-content" v-if="modal.method == 'datos'" id="datos">                    
                            
                            
                            <div class="modal-body" style="padding: 0.8rem 1.3rem">
                                <button type="button" class="btn-close" data-dismiss="modal" @click="CloseModal"><i class="fas fa-times"></i></button>
                                <div class="text-center mb-4 mt-4">
                                    <h3>Editar Perfil</h3>
                                </div>

                                <div class="form-row m-2">
                                    <div class="col-md-12 obligatorio text-right" style="max-height: 15px;">
                                        *<small style="vertical-align: top;"> Obligatorio</small>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="nombres">NOMBRES COMPLETOS <span class="obligatorio">*</span></label>
                                        <input type="text" id="nombres" v-model="user.nombres" class="form-control" :class="[errors.nombres ? 'border-error' : '']">
                                        <div class="input-error" v-if="errors.nombres">@{{ errors.nombres[0] }}</div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="dni">DNI <span class="obligatorio">*</span></label>
                                        <input type="text" id="dni" v-model="user.dni" class="form-control" :class="[errors.dni ? 'border-error' : '']" maxlength="8" onkeypress="Numero()">
                                        <div class="input-error" v-if="errors.dni">@{{ errors.dni[0] }}</div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="telefono">TELEFONO </label>
                                        <input type="text" id="telefono" v-model="user.telefono" class="form-control" :class="[errors.telefono ? 'border-error' : '']" maxlength="9" onkeypress="Numero()">
                                        <div class="input-error" v-if="errors.telefono">@{{ errors.telefono[0] }}</div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="email">EMAIL <span class="obligatorio">*</span></label>
                                        <input type="email" id="email" v-model="user.email" class="form-control" :class="[errors.email ? 'border-error' : '']" maxlength="9" onkeypress="Numero()">
                                        <div class="input-error" v-if="errors.email">@{{ errors.email[0] }}</div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="username">NOMBRE DE USUARIO <span class="obligatorio">*</span></label>
                                        <input type="username" id="username" v-model="user.username" class="form-control" :class="[errors.username ? 'border-error' : '']" maxlength="9" onkeypress="Numero()">
                                        <div class="input-error" v-if="errors.username">@{{ errors.username[0] }}</div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="password">NUEVA CONTRASEÑA</label>
                                        <div>
                                            <span class="show-password" v-if="!show_password" @click="Show(1)"><i class="fa fa-eye"></i></span>
                                            <span class="show-password" v-else @click="Hide(1)"><i class="fa fa-eye-slash"></i></span>
                                            <input type="password" id="password" v-model="user.password" class="form-control" :class="[errors.password ? 'border-error' : '']">
                                        </div>
                                        <div class="input-error" v-if="errors.password">@{{ errors.password[0] }}</div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="rpassword">CONFIRMAR CONTRASEÑA</label>
                                        <div>
                                            <span class="show-password" v-if="!show_rpassword" @click="Show(2)"><i class="fa fa-eye"></i></span>
                                            <span class="show-password" v-else @click="Hide(2)"><i class="fa fa-eye-slash"></i></span>
                                            <input type="password" id="rpassword" v-model="user.password_confirmation" class="form-control" :class="[errors.password_confirmation ? 'border-error' : '']">
                                        </div>
                                        <div class="input-error" v-if="errors.password_confirmation">@{{ errors.password_confirmation[0] }}</div>
                                    </div>
                                    <div class="col-md-12 mt-3 mb-4">
                                        <button class="btn btn-block btn-secondary" @click="Perfil('datos')"><i class="fa fa-check-square"></i> Guardar Cambios</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- MODAL -->

                <div class="col-lg-1"></div>
                <div class="col-lg-4 col-md-12">
                    <div class="card card-warning card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                    src="{{asset('img/auth.png')}}"
                                    alt="User profile picture">
                            </div>
          
                            <h3 class="profile-username text-center">@{{perfil.x_nombres}}</h3>
          
                            <p class="text-muted text-center">@{{perfil.get_rol.x_nombre}}</p>
            
                            <hr>
                            <strong><i class="far fa-address-card"></i> Documento de Identidad (DNI)</strong>
                            <p class="text-muted">@{{perfil.c_dni}}</p>
                            <hr>
                            <strong><i class="far fa-envelope-open"></i> Correo Electrónico</strong>
                            <p class="text-muted">@{{perfil.x_email}}</p>
                            <hr>
                            <strong><i class="far fa-user"></i> Nombre de Usuario</strong>
                            <p class="text-muted">@{{perfil.username}}</p>
                            <hr>
            
                            <a href="#" class="btn btn-warning btn-block" data-toggle="modal" data-target="#formularioModal" @click="Modal('modal-md', 'datos', null, null)"> Editar Datos</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        var my_perfil = {!! json_encode($perfil) !!}
    </script>
    <script src="{{asset('views/perfil.js')}}"></script>
@endsection