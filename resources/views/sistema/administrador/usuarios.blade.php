@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1><i class="fas fa-key"></i> ACCESO DE USUARIOS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item active">Usuarios</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row" style="padding-top: 10px;" id="usuarios">
                <div class="col-12">
                    <div class="card card-dark card-outline">
                        <div class="card-header">
                            <h3 class="card-title">LISTA DE USUARIOS</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" @click="Buscar">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                    <i class="fas fa-expand"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div> 

                        <!-- MODAL -->
                        <div class="modal fade" id="formularioModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog" role="document" :class="modal.size">

                                {{-- CREATE --}}
                                <div class="modal-content" v-if="modal.method == 'create'" id="create">

                                    <div class="modal-body" style="padding: 0.8rem 1.3rem">
                                        <button type="button" class="btn-close" data-dismiss="modal" @click="CloseModal"><i class="fas fa-times"></i></button>
                                        <div class="text-center mb-4 mt-4">
                                            <h3>NUEVO USUARIO</h3>
                                        </div>
                                        <div class="card card-primary card-outline">
                                            <div class="m-2">
                                                <div class="form-row">
                                                    <div class="col-md-12 obligatorio text-right" style="max-height: 15px;">
                                                        *<small style="vertical-align: top;"> Obligatorio</small>
                                                    </div>
        
                                                    <div class="form-group col-md-12">
                                                        <label for="sede">SEDE <span class="obligatorio">*</span></label>
                                                        <select id="sede" v-model="user.sede" class="form-control" :class="[errors.sede ? 'border-error' : '']" @change="FilterDependencias">
                                                            <option value="">--- Seleccione Opción ---</option>
                                                            @foreach ($sedes as $sed)
                                                                <option value="{{$sed->id}}">{{$sed->c_codigo.': '.$sed->x_nombre}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-error" v-if="errors.sede">@{{ errors.sede[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="dependencia">DEPENDENCIA <span class="obligatorio">*</span></label>
                                                        <select id="dependencia" v-model="user.dependencia" class="form-control" :class="[errors.dependencia ? 'border-error' : '']">
                                                            <option value="">--- Seleccione Opción ---</option>
                                                            @foreach ($dependencias as $dep)
                                                                <option value="{{$dep->id}}">{{$dep->c_codigo.': '.$dep->x_nombre}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-error" v-if="errors.dependencia">@{{ errors.dependencia[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="nombres">NOMBRES Y APELLIDOS <span class="obligatorio">*</span></label>
                                                        <input type="text" id="nombres" v-model="user.nombres" class="form-control" :class="[errors.nombres ? 'border-error' : '']">
                                                        <div class="input-error" v-if="errors.nombres">@{{ errors.nombres[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="dni">DNI <span class="obligatorio">*</span></label>
                                                        <input type="text" id="dni" v-model="user.dni" class="form-control" :class="[errors.dni ? 'border-error' : '']" maxlength="8" onkeypress="Numero()" autocomplete="off">
                                                        <div class="input-error" v-if="errors.dni">@{{ errors.dni[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="telefono">TELEFONO</label>
                                                        <input type="text" id="telefono" v-model="user.telefono" class="form-control" :class="[errors.telefono ? 'border-error' : '']" maxlength="9" onkeypress="Numero()">
                                                        <div class="input-error" v-if="errors.telefono">@{{ errors.telefono[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="email">EMAIL <span class="obligatorio">*</span></label>
                                                        <input type="email" id="email" v-model="user.email" class="form-control" :class="[errors.email ? 'border-error' : '']">
                                                        <div class="input-error" v-if="errors.email">@{{ errors.email[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="rol">ROL / PERFIL <span class="obligatorio">*</span></label>
                                                        <select id="rol" v-model="user.rol" class="form-control" :class="[errors.rol ? 'border-error' : '']">
                                                            <option value="">--- Seleccionar Opción ---</option>
                                                            @foreach ($roles as $rol)
                                                                <option value="{{$rol->id}}">{{$rol->x_nombre}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-error" v-if="errors.rol">@{{ errors.rol[0] }}</div>
                                                    </div>
                                                </div>
        
                                                <div class="form-row">
                                                    <div class="col-md-12 mt-3 mb-2" style="border-bottom: 1px solid rgba(0,0,0,.1);">
                                                        <h5 style="font-size: 15px;">DATOS DE ACCESO (Login)</h5>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="username">NOMBRE DE USUARIO <span class="obligatorio">*</span></label>
                                                        <input type="text" id="username" v-model="user.username" class="form-control" :class="[errors.username ? 'border-error' : '']">
                                                        <div class="input-error" v-if="errors.username">@{{ errors.username[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="password">CONTRASEÑA <span class="obligatorio">*</span></label>
                                                        <div>
                                                            <span class="show-password" v-if="!show_password" @click="Show"><i class="fa fa-eye"></i></span>
                                                            <span class="show-password" v-else @click="Hide"><i class="fa fa-eye-slash"></i></span>
                                                            <input type="password" id="password" v-model="user.password" class="form-control" :class="[errors.password ? 'border-error' : '']">
                                                        </div>
                                                        <div class="input-error" v-if="errors.password">@{{ errors.password[0] }}</div>
                                                    </div>
        
                                                    <div class="col-md-12 mt-3 mb-4">
                                                        <button class="btn btn-block bg-gradient-primary" @click="Store('create')"><i class="fa fa-check-square"></i> Guardar registro</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- CREATE --}}

                                {{-- EDIT --}}
                                <div class="modal-content" v-if="modal.method == 'edit'" id="edit">
                                    <div class="modal-body" style="padding: 0.8rem 1.3rem">
                                        <button type="button" class="btn-close" data-dismiss="modal" @click="CloseModal"><i class="fas fa-times"></i></button>
                                        <div class="text-center mb-4 mt-4">
                                            <h3>EDITAR USUARIO</h3>
                                        </div>
                                        <div class="card card-warning card-outline">
                                            <div class="m-2">
                                                <div class="form-row">
                                                    <div class="col-md-12 obligatorio text-right" style="max-height: 15px;">
                                                        *<small style="vertical-align: top;"> Obligatorio</small>
                                                    </div>
        
                                                    <div class="form-group col-md-12">
                                                        <label for="sede">SEDE <span class="obligatorio">*</span></label>
                                                        <select id="sede" v-model="user.sede" class="form-control" :class="[errors.sede ? 'border-error' : '']" @change="FilterDependencias">
                                                            <option value="">--- Seleccione Opción ---</option>
                                                            @foreach ($sedes as $sed)
                                                                <option value="{{$sed->id}}">{{$sed->c_codigo.': '.$sed->x_nombre}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-error" v-if="errors.sede">@{{ errors.sede[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="dependencia">DEPENDENCIA <span class="obligatorio">*</span></label>
                                                        <select id="dependencia" v-model="user.dependencia" class="form-control" :class="[errors.dependencia ? 'border-error' : '']">
                                                            <option value="">--- Seleccione Opción ---</option>
                                                            @foreach ($dependencias as $dep)
                                                                <option value="{{$dep->id}}">{{$dep->c_codigo.': '.$dep->x_nombre}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-error" v-if="errors.dependencia">@{{ errors.dependencia[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="nombres">NOMBRES Y APELLIDOS <span class="obligatorio">*</span></label>
                                                        <input type="text" id="nombres" v-model="user.nombres" class="form-control" :class="[errors.nombres ? 'border-error' : '']">
                                                        <div class="input-error" v-if="errors.nombres">@{{ errors.nombres[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="dni">DNI <span class="obligatorio">*</span></label>
                                                        <input type="text" id="dni" v-model="user.dni" class="form-control" :class="[errors.dni ? 'border-error' : '']" maxlength="8" onkeypress="Numero()" autocomplete="off">
                                                        <div class="input-error" v-if="errors.dni">@{{ errors.dni[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="telefono">TELEFONO</label>
                                                        <input type="text" id="telefono" v-model="user.telefono" class="form-control" :class="[errors.telefono ? 'border-error' : '']" maxlength="9" onkeypress="Numero()">
                                                        <div class="input-error" v-if="errors.telefono">@{{ errors.telefono[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="email">EMAIL <span class="obligatorio">*</span></label>
                                                        <input type="email" id="email" v-model="user.email" class="form-control" :class="[errors.email ? 'border-error' : '']">
                                                        <div class="input-error" v-if="errors.email">@{{ errors.email[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="rol">ROL / PERFIL <span class="obligatorio">*</span></label>
                                                        <select id="rol" v-model="user.rol" class="form-control" :class="[errors.rol ? 'border-error' : '']">
                                                            <option value="">--- Seleccionar Opción ---</option>
                                                            @foreach ($roles as $rol)
                                                                <option value="{{$rol->id}}">{{$rol->x_nombre}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-error" v-if="errors.rol">@{{ errors.rol[0] }}</div>
                                                    </div>
                                                </div>
        
                                                <div class="form-row">
                                                    <div class="col-md-12 mt-3 mb-2" style="border-bottom: 1px solid rgba(0,0,0,.1);">
                                                        <h5 style="font-size: 15px;">DATOS DE ACCESO (Login)</h5>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="username">NOMBRE DE USUARIO <span class="obligatorio">*</span></label>
                                                        <input type="text" id="username" v-model="user.username" class="form-control" :class="[errors.username ? 'border-error' : '']">
                                                        <div class="input-error" v-if="errors.username">@{{ errors.username[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="password">CONTRASEÑA <span class="obligatorio">*</span></label>
                                                        <div>
                                                            <span class="show-password" v-if="!show_password" @click="Show"><i class="fa fa-eye"></i></span>
                                                            <span class="show-password" v-else @click="Hide"><i class="fa fa-eye-slash"></i></span>
                                                            <input type="password" id="password" v-model="user.password" class="form-control" :class="[errors.password ? 'border-error' : '']">
                                                        </div>
                                                        <div class="input-error" v-if="errors.password">@{{ errors.password[0] }}</div>
                                                    </div>

                                                    <div class="col-md-12 mt-3 mb-4">
                                                        <button class="btn btn-block btn-warning" @click="Update('edit')"><i class="fa fa-check-square"></i> Actualizar registro</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- EDIT --}}

                                {{-- DELETE --}}
                                <div class="modal-content" v-if="modal.method == 'delete'" id="delete">
                                    <div class="modal-body text-center" style="padding: 0.8rem 1.3rem">
                                        <button type="button" class="btn-close" data-dismiss="modal" @click="CloseModal"><i class="fas fa-times"></i></button>
                                        <div class="text-center mb-4 mt-4">
                                            <h3>ELIMINAR USUARIO</h3>
                                        </div>
                                        <div class="card card-danger card-outline">
                                            <div class="m-2">
                                                <p class="text-center">¿ Realmente desea eliminar el usuario: <br>
                                                    <strong>@{{user.nombres}}</strong> ?</p>

                                                <div class="mt-4 mb-4 text-center">
                                                    <button class="btn btn-danger" @click="Delete('delete')" style="width: 300px;"><i class="fa fa-check-square"></i> Eliminar registro</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- DELETE --}}

                                {{-- ACTIVAR --}}
                                <div class="modal-content" v-if="modal.method == 'activar'" id="activar">
                                    <div class="modal-body text-center" style="padding: 0.8rem 1.3rem">
                                        <button type="button" class="btn-close" data-dismiss="modal" @click="CloseModal"><i class="fas fa-times"></i></button>
                                        <div class="text-center mb-4 mt-4">
                                            <h3>ACTIVAR USUARIO</h3>
                                        </div>

                                        <div class="m-2">
                                            <p class="text-center">¿ Realmente desea Activar la cuenta del usuario: <br>
                                                <strong>@{{user.nombres}}</strong> ?</p>

                                            <div class="mt-4 mb-4 text-center">
                                                <button class="btn btn-success" @click="Activar('activar')" style="width: 300px;"><i class="fa fa-check-square"></i> Activar registro</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- ACTIVAR --}}
                            </div>
                        </div>
                        <!-- MODAL -->

                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-lg-8">
                                    @if ($opcion->l_crear == 'S')
                                    <button type="button" class="btn bg-gradient-primary" style="width: 100px;" data-toggle="modal" data-target="#formularioModal" @click="Modal('modal-md', 'create', null, null)"><i class="fas fa-plus"></i> Nuevo</button>
                                    @endif
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-default btn-md dropdown-toggle" data-toggle="dropdown"><i class="fas fa-filter"></i> Filtrar por</button>
                                            <div class="dropdown-menu dropdown-sm">
                                                <a class="dropdown-item" href="#" :class="[(search.filter == 1) ? 'active' : '']" @click="Filtrar('Datos del Usuario', 1)">Nombre</a>
                                                <a class="dropdown-item" href="#" :class="[(search.filter == 2) ? 'active' : '']" @click="Filtrar('Datos del Perfil', 2)">Perfil</a>
                                            </div>
                                        </div>
                                        <input type="text" v-model="search.datos" class="form-control" :placeholder="search.text_filter" @keyup.enter="Buscar">
                                        <div class="input-group-append">
                                            <button type="button" class="btn bg-gradient-success" @click="Buscar"><i class="fas fa-search"></i> Buscar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive" id="my_table" style="min-height: 140px;">
                                <table class="table table-md table-hover">
                                    <thead>
                                        <tr style="background-color: #f2f2f2;">
                                            <th class="text-center">#</th>
                                            <th>APELLIDOS Y NOMBRES</th>
                                            <th>DNI</th>
                                            <th>EMAIL</th>
                                            <th>SEDE</th>
                                            <th>DEPENDENCIA</th>
                                            <th class="text-center">ROL</th>
                                            <th class="text-center">OPCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(data, index) in listRequest" :class="'active-'+data.l_activo">
                                            <td class="text-center">@{{(index + pagination.index + 1)}}</td>
                                            <td>@{{data.x_nombres}}</td>
                                            <td>@{{data.c_dni}}</td>
                                            <td>@{{data.x_email}}</td>
                                            <td>
                                                <strong><i class="fas fa-map-marked-alt"></i> </strong>: @{{data.get_sede.x_nombre}}
                                            </td>
                                            <td>
                                                <strong><i class="fas fa-landmark"></i> </strong>: @{{data.get_dependencia.x_nombre}}
                                            </td>
                                            <td class="text-center">@{{data.get_rol.x_nombre}}</td>
                                            <td class="text-center">
                                                @if (($opcion->l_editar == 'S') || ($opcion->l_eliminar == 'S'))
                                                    <div class="">
                                                        <button type="button" class="btn btn-default pt-0 pb-0" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                                        <div class="dropdown-menu">
                                                            <template v-if="data.l_activo == 'S'">
                                                                @if ($opcion->l_editar == 'S')
                                                                    <a href="#" class="dropdown-item text-warning" data-toggle="modal" data-target="#formularioModal" 
                                                                    v-on:click="Modal('modal-md', 'edit', data.id, data)"><i class="fas fa-pen-square"></i> Editar</a>
                                                                @endif
                
                                                                @if ($opcion->l_eliminar == 'S')
                                                                    <a href="#" class="dropdown-item text-danger" data-toggle="modal" data-target="#formularioModal" 
                                                                    v-on:click="Modal('modal-md', 'delete', data.id, data)"><i class="fas fa-window-close"></i> Eliminar</a>
                                                                @endif
                                                            </template>
                                                            <template v-else>
                                                                @if ($opcion->l_activar == 'S')
                                                                    <a href="#" class="dropdown-item text-success" data-toggle="modal" data-target="#formularioModal" 
                                                                    v-on:click="Modal('modal-md', 'activar', data.id, data)"><i class="fas fa-check-square"></i> Volver Activar</a>
                                                                @endif
                                                            </template>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-sm-12"> Mostrando @{{to_pagination+'/'+pagination.total}} registros</div>
                                <div class="col-lg-6 col-sm-12">
                                    <ul class="pagination pagination-sm justify-content-end mb-0">
                                        <template v-if="pagination.current_page > 1">
                                            <li class="page-item inicio">
                                                <a class="page-link" href="#" title="Ir al inicio" @click.prevent="changePage(1)"><i class="fas fa-arrow-left" style="vertical-align: middle;"></i></a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#" title="Anterior" @click.prevent="changePage(pagination.current_page - 1)"><i class="fas fa-chevron-left" style="vertical-align: middle;"></i></a>
                                            </li>
                                        </template>
                                        <template v-else>
                                            <li class="page-item inicio disabled">
                                                <a class="page-link" href="#" title="Ir al inicio"><i class="fas fa-arrow-left" style="vertical-align: middle;"></i></a>
                                            </li>
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" title="Anterior"><i class="fas fa-chevron-left" style="vertical-align: middle;"></i></a>
                                            </li>
                                        </template>

                                        <li class="page-item active"><a class="page-link" href="#">@{{pagination.current_page}}</a></li>

                                        <template v-if="pagination.current_page < pagination.last_page">
                                            <li class="page-item">
                                                <a class="page-link" href="#" title="Siguiente" @click.prevent="changePage(pagination.current_page + 1)"><i class="fas fa-chevron-right" style="vertical-align: middle;"></i></a>
                                            </li>
                                            <li class="page-item final">
                                                <a class="page-link" href="#" aria-label="Next" title="Ir al final" @click.prevent="changePage(pagination.last_page)"><i class="fas fa-arrow-right" style="vertical-align: middle;"></i></a>
                                            </li>
                                        </template>
                                        <template v-else>
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" title="Siguiente"><i class="fas fa-chevron-right" style="vertical-align: middle;"></i></a>
                                            </li>
                                            <li class="page-item final disabled">
                                                <a class="page-link" href="#" aria-label="Next" title="Ir al final"><i class="fas fa-arrow-right" style="vertical-align: middle;"></i></a>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>

                            <div>
                                {{-- <div class="overlay-wrapper">
                                    <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin malesuada lacus ullamcorper dui molestie, sit amet congue quam finibus. Etiam ultricies nunc non magna feugiat commodo. Etiam odio magna, mollis auctor felis vitae, ullamcorper ornare ligula. Proin pellentesque tincidunt nisi, vitae ullamcorper felis aliquam id. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Proin id orci eu lectus blandit suscipit. Phasellus porta, ante et varius ornare, sem enim sollicitudin eros, at commodo leo est vitae lacus. Etiam ut porta sem. Proin porttitor porta nisl, id tempor risus rhoncus quis. In in quam a nibh cursus pulvinar non consequat neque. Mauris lacus elit, condimentum ac condimentum at, semper vitae lectus. Cras lacinia erat eget sapien porta consectetur.
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="{{asset('views/administrador/usuarios.js')}}"></script>
@endsection