@extends('layouts.app')

@section('css')
    <style>
        .custom-control-label {
            position: relative;
            margin-bottom: 0;
            vertical-align: top;
            padding-top: 2px;
            font-weight: 500 !important;
        }
        .permisos {
            border: 2px solid #c1c1c1;
            border-radius: 5px;
            padding: 10px 15px;
        }
        .text-modulo {
            background-color: #fb4d4d;
            color: #fff;
        }
        .menu {
            font-size: 14px;
            padding-right: 10px;
        }
        .submenu {
            font-size: 14px;
        }
        .modulo {
            font-size: 14px;
            padding-right: 10px;
        }
        .modulo span {
            border: 1px solid #c1c1c1;
            border-radius: 5px;
            font-size: 14px;
            background-color: #fb4d4d;
            color: #fb4d4d;
        }
        .menu span {
            border: 1px solid #c1c1c1;
            border-radius: 5px;
            font-size: 14px;
            background-color: #ededed;
            color: #ededed;
        }
        .submenu span {
            border: 1px solid #c1c1c1;
            border-radius: 5px;
            font-size: 14px;
            background-color: #ffffc8;
            color: #ffffc8;
        }
        .input-menu {
            font-size: 16px;
            padding-top: 0;
            text-transform: uppercase;
        }
        .custom-checkbox .custom-control-input:disabled:checked~.custom-control-label::before {
            background-color: #d1d1d180;
            border-color: #adb5bd;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1><i class="fas fa-user-lock"></i> ROLES</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item active">Roles</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row" style="padding-top: 10px;" id="roles">
                <div class="col-12">
                    <div class="card card-dark card-outline">
                        <div class="card-header">
                            <h3 class="card-title">LISTA DE ROLES</h3>
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
                                            <h3>NUEVO ROL</h3>
                                        </div>
                                        <div class="card card-primary card-outline">
                                            <div class="m-2">
                                                <div class="form-row">
                                                    <div class="col-md-12 obligatorio text-right" style="max-height: 15px;">
                                                        *<small style="vertical-align: top;"> Obligatorio</small>
                                                    </div>
        
                                                    <div class="form-group col-md-12 mb-3">
                                                        <label for="nombre">NOMBRE DE ROL <span class="obligatorio">*</span></label>
                                                        <input type="text" id="nombre" v-model="rol.nombre" class="form-control" :class="[errors.nombre ? 'border-error' : '']">
                                                        <div class="input-error" v-if="errors.nombre">@{{ errors.nombre[0] }}</div>
                                                    </div>    
                                                </div>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="permisos">
                                                            <h4>
                                                                PERMISOS DEL ROL
                                                                {{-- <span class="submenu float-right"><span>111</span> submenu</span>
                                                                <span class="menu float-right"><span>111</span> menu</span>
                                                                <span class="modulo float-right"><span>111</span> modulo</span> --}}
                                                            </h4>
                                                            
                                                            <div class="card card-secondary" v-for="modulo in permisos">
                                                                <div class="card-header p-1 pl-3 pr-3">
                                                                    <h5 class="card-title" style="font-size: 18px;">@{{modulo.nombre}}</h5>
                                                                    <div class="card-tools">
                                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                            <i class="fas fa-minus"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body p-2">
                                                                    <div class="table-responsive">            
                                                                        <table class="table table-md mb-0" v-if="modulo.get_menus.length > 0">
                                                                            <template v-for="menu in modulo.get_menus">
                                                                                <template v-if="menu.submenus == 'S'">
                                                                                    <tr style="">
                                                                                        <td colspan="5" style="font-weight: bold; text-transform: uppercase; font-size: 16px;">@{{menu.nombre}}</td>
                                                                                    </tr>
                                                                                    <tr v-for="submenu in menu.get_submenus" v-if="submenu.activo == 'S'" style="">
                                                                                        <td width="61%" style="font-weight: bold;">
                                                                                            <div class="custom-control custom-checkbox">
                                                                                                <input class="custom-control-input custom-control-input-dark" type="checkbox" :id="'submenu_ver'+submenu.id" 
                                                                                                :class="'menu'+menu.id" v-model="submenu.ver" true-value="S" false-value="N">
                                                                                                <label :for="'submenu_ver'+submenu.id" class="custom-control-label input-menu">@{{submenu.nombre}}</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td width="13%">
                                                                                            <div class="custom-control custom-checkbox">
                                                                                                <input class="custom-control-input custom-control-input-secondary" type="checkbox" :id="'submenu_crear'+submenu.id" 
                                                                                                :class="'menu'+menu.id" v-model="submenu.crear" true-value="S" false-value="N" :disabled="submenu.ver == 'N'">
                                                                                                <label :for="'submenu_crear'+submenu.id" class="custom-control-label">Crear</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td width="13%">
                                                                                            <div class="custom-control custom-checkbox">
                                                                                                <input class="custom-control-input custom-control-input-secondary" type="checkbox" :id="'submenu_editar'+submenu.id" 
                                                                                                :class="'menu'+menu.id" v-model="submenu.editar" true-value="S" false-value="N" :disabled="submenu.ver == 'N'">
                                                                                                <label :for="'submenu_editar'+submenu.id" class="custom-control-label">Editar</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td width="13%">
                                                                                            <div class="custom-control custom-checkbox">
                                                                                                <input class="custom-control-input custom-control-input-secondary" type="checkbox" :id="'submenu_eliminar'+submenu.id" 
                                                                                                :class="'menu'+menu.id" v-model="submenu.eliminar" true-value="S" false-value="N" :disabled="submenu.ver == 'N'">
                                                                                                <label :for="'submenu_eliminar'+submenu.id" class="custom-control-label">Eliminar</label>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </template>
                                                                                <template v-else>
                                                                                    <tr style="">
                                                                                        <td width="61%" style="font-weight: bold;">
                                                                                            <div class="custom-control custom-checkbox">
                                                                                                <input class="custom-control-input custom-control-input-dark" type="checkbox" :id="'submenu_ver'+menu.get_submenus[0].id" 
                                                                                                :class="'menu'+menu.get_submenus[0].id" v-model="menu.get_submenus[0].ver" true-value="S" false-value="N">
                                                                                                <label :for="'submenu_ver'+menu.get_submenus[0].id" class="custom-control-label input-menu">@{{menu.get_submenus[0].nombre}}</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td width="13%">
                                                                                            <div class="custom-control custom-checkbox">
                                                                                                <input class="custom-control-input custom-control-input-secondary" type="checkbox" :id="'submenu_crear'+menu.get_submenus[0].id" 
                                                                                                :class="'menu'+menu.get_submenus[0].id" v-model="menu.get_submenus[0].crear" true-value="S" false-value="N" :disabled="menu.get_submenus[0].ver == 'N'">
                                                                                                <label :for="'submenu_crear'+menu.get_submenus[0].id" class="custom-control-label">Crear</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td width="13%">
                                                                                            <div class="custom-control custom-checkbox">
                                                                                                <input class="custom-control-input custom-control-input-secondary" type="checkbox" :id="'submenu_editar'+menu.get_submenus[0].id" 
                                                                                                :class="'menu'+menu.get_submenus[0].id" v-model="menu.get_submenus[0].editar" true-value="S" false-value="N" :disabled="menu.get_submenus[0].ver == 'N'">
                                                                                                <label :for="'submenu_editar'+menu.get_submenus[0].id" class="custom-control-label">Editar</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td width="13%">
                                                                                            <div class="custom-control custom-checkbox">
                                                                                                <input class="custom-control-input custom-control-input-secondary" type="checkbox" :id="'submenu_eliminar'+menu.get_submenus[0].id" 
                                                                                                :class="'menu'+menu.get_submenus[0].id" v-model="menu.get_submenus[0].eliminar" true-value="S" false-value="N" :disabled="menu.get_submenus[0].ver == 'N'">
                                                                                                <label :for="'submenu_eliminar'+menu.get_submenus[0].id" class="custom-control-label">Eliminar</label>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </template>
                                                                            </template>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                                                                                
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-12 mt-4 mb-4">
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
                                            <h3>EDITAR ROL</h3>
                                        </div>
                                        <div class="card card-warning card-outline">
                                            <div class="m-2">
                                                <div class="form-row">
                                                    <div class="col-md-12 obligatorio text-right" style="max-height: 15px;">
                                                        *<small style="vertical-align: top;"> Obligatorio</small>
                                                    </div>
        
                                                    <div class="form-group col-md-12 mb-3">
                                                        <label for="nombre">NOMBRE DE ROL <span class="obligatorio">*</span></label>
                                                        <input type="text" id="nombre" v-model="rol.nombre" class="form-control" :class="[errors.nombre ? 'border-error' : '']">
                                                        <div class="input-error" v-if="errors.nombre">@{{ errors.nombre[0] }}</div>
                                                    </div>    
                                                </div>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="permisos">
                                                            <h4>
                                                                PERMISOS DEL ROL
                                                                {{-- <span class="submenu float-right"><span>111</span> submenu</span>
                                                                <span class="menu float-right"><span>111</span> menu</span>
                                                                <span class="modulo float-right"><span>111</span> modulo</span> --}}
                                                            </h4>
                                                            
                                                            <div class="card card-secondary" v-for="modulo in permisos">
                                                                <div class="card-header p-1 pl-3 pr-3">
                                                                    <h5 class="card-title" style="font-size: 18px;">@{{modulo.nombre}}</h5>
                                                                    <div class="card-tools">
                                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                            <i class="fas fa-minus"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body p-2">
                                                                    <div class="table-responsive">            
                                                                        <table class="table table-md mb-0" v-if="modulo.get_menus.length > 0">
                                                                            <template v-for="menu in modulo.get_menus">
                                                                                <template v-if="menu.submenus == 'S'">
                                                                                    <tr style="">
                                                                                        <td colspan="5" style="font-weight: bold; text-transform: uppercase; font-size: 16px;">@{{menu.nombre}}</td>
                                                                                    </tr>
                                                                                    <tr v-for="submenu in menu.get_submenus" v-if="submenu.activo == 'S'" style="">
                                                                                        <td width="61%" style="font-weight: bold;">
                                                                                            <div class="custom-control custom-checkbox">
                                                                                                <input class="custom-control-input custom-control-input-dark" type="checkbox" :id="'submenu_ver'+submenu.id" 
                                                                                                :class="'menu'+menu.id" v-model="submenu.ver" true-value="S" false-value="N">
                                                                                                <label :for="'submenu_ver'+submenu.id" class="custom-control-label input-menu">@{{submenu.nombre}}</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td width="13%">
                                                                                            <div class="custom-control custom-checkbox">
                                                                                                <input class="custom-control-input custom-control-input-secondary" type="checkbox" :id="'submenu_crear'+submenu.id" 
                                                                                                :class="'menu'+menu.id" v-model="submenu.crear" true-value="S" false-value="N" :disabled="submenu.ver == 'N'">
                                                                                                <label :for="'submenu_crear'+submenu.id" class="custom-control-label">Crear</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td width="13%">
                                                                                            <div class="custom-control custom-checkbox">
                                                                                                <input class="custom-control-input custom-control-input-secondary" type="checkbox" :id="'submenu_editar'+submenu.id" 
                                                                                                :class="'menu'+menu.id" v-model="submenu.editar" true-value="S" false-value="N" :disabled="submenu.ver == 'N'">
                                                                                                <label :for="'submenu_editar'+submenu.id" class="custom-control-label">Editar</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td width="13%">
                                                                                            <div class="custom-control custom-checkbox">
                                                                                                <input class="custom-control-input custom-control-input-secondary" type="checkbox" :id="'submenu_eliminar'+submenu.id" 
                                                                                                :class="'menu'+menu.id" v-model="submenu.eliminar" true-value="S" false-value="N" :disabled="submenu.ver == 'N'">
                                                                                                <label :for="'submenu_eliminar'+submenu.id" class="custom-control-label">Eliminar</label>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </template>
                                                                                <template v-else>
                                                                                    <tr style="">
                                                                                        <td width="61%" style="font-weight: bold;">
                                                                                            <div class="custom-control custom-checkbox">
                                                                                                <input class="custom-control-input custom-control-input-dark" type="checkbox" :id="'submenu_ver'+menu.get_submenus[0].id" 
                                                                                                :class="'menu'+menu.get_submenus[0].id" v-model="menu.get_submenus[0].ver" true-value="S" false-value="N">
                                                                                                <label :for="'submenu_ver'+menu.get_submenus[0].id" class="custom-control-label input-menu">@{{menu.get_submenus[0].nombre}}</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td width="13%">
                                                                                            <div class="custom-control custom-checkbox">
                                                                                                <input class="custom-control-input custom-control-input-secondary" type="checkbox" :id="'submenu_crear'+menu.get_submenus[0].id" 
                                                                                                :class="'menu'+menu.get_submenus[0].id" v-model="menu.get_submenus[0].crear" true-value="S" false-value="N" :disabled="menu.get_submenus[0].ver == 'N'">
                                                                                                <label :for="'submenu_crear'+menu.get_submenus[0].id" class="custom-control-label">Crear</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td width="13%">
                                                                                            <div class="custom-control custom-checkbox">
                                                                                                <input class="custom-control-input custom-control-input-secondary" type="checkbox" :id="'submenu_editar'+menu.get_submenus[0].id" 
                                                                                                :class="'menu'+menu.get_submenus[0].id" v-model="menu.get_submenus[0].editar" true-value="S" false-value="N" :disabled="menu.get_submenus[0].ver == 'N'">
                                                                                                <label :for="'submenu_editar'+menu.get_submenus[0].id" class="custom-control-label">Editar</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td width="13%">
                                                                                            <div class="custom-control custom-checkbox">
                                                                                                <input class="custom-control-input custom-control-input-secondary" type="checkbox" :id="'submenu_eliminar'+menu.get_submenus[0].id" 
                                                                                                :class="'menu'+menu.get_submenus[0].id" v-model="menu.get_submenus[0].eliminar" true-value="S" false-value="N" :disabled="menu.get_submenus[0].ver == 'N'">
                                                                                                <label :for="'submenu_eliminar'+menu.get_submenus[0].id" class="custom-control-label">Eliminar</label>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </template>
                                                                            </template>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>                                                        
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-12 mt-4 mb-4">
                                                        <button class="btn btn-block bg-gradient-warning" @click="Update('edit')"><i class="fa fa-check-square"></i> Actualizar registro</button>
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
                                            <h3>ELIMINAR ROL</h3>
                                        </div>
                                        <div class="card card-danger card-outline">
                                            <div class="m-2">
                                                <p class="text-center">¿ Realmente desea eliminar el Rol: <br>
                                                    <strong>@{{rol.nombre}}</strong> ?</p>

                                                <div class="mt-4 mb-4 text-center">
                                                    <button class="btn btn-danger" @click="Delete('delete')" style="width: 300px;"><i class="fa fa-check-square"></i> Eliminar registro</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- DELETE --}}
                            </div>
                        </div>
                        <!-- MODAL -->

                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-lg-8">
                                    @if ($opcion->l_crear == 'S')
                                    <button type="button" class="btn bg-gradient-primary" style="width: 100px;" data-toggle="modal" data-target="#formularioModal" @click="Modal('modal-lg', 'create', null, null)"><i class="fas fa-plus"></i> Nuevo</button>
                                    @endif
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-default btn-md dropdown-toggle" data-toggle="dropdown"><i class="fas fa-filter"></i> Filtrar por</button>
                                            <div class="dropdown-menu dropdown-sm">
                                                <a class="dropdown-item" href="#" :class="[(search.filter == 1) ? 'active' : '']" @click="Filtrar('Datos del Usuario', 1)">Nombre</a>
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
                                            <th>NOMBRE DEL ROL</th>
                                            <th># USUARIOS</th>
                                            <th class="text-center">OPCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(data, index) in listRequest" :class="'active-'+data.l_activo">
                                            <td width="5%" class="text-center">@{{(index + pagination.index + 1)}}</td>
                                            <td width="60%">@{{data.x_nombre}}</td>
                                            <td width="20%" class="project_progress">
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar bg-green" role="progressbar" :aria-valuenow="data.get_users_count" aria-valuemin="0" aria-valuemax="100" :style="'width:'+data.get_users_count+'%'">
                                                    </div>
                                                </div>
                                                <small>
                                                    @{{data.get_users_count}}% Cuentas
                                                </small>
                                            </td>
                                            <td width="15%" class="text-center">
                                                @if (($opcion->l_editar == 'S') || ($opcion->l_eliminar == 'S'))
                                                    <div class="">
                                                        <button type="button" class="btn btn-default pt-0 pb-0" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                                        <div class="dropdown-menu">
                                                            @if ($opcion->l_editar == 'S')
                                                                <a href="#" class="dropdown-item text-warning" data-toggle="modal" data-target="#formularioModal" 
                                                                v-on:click="Modal('modal-lg', 'edit', data.id, data)"><i class="fas fa-pen-square"></i> Editar</a>
                                                            @endif
            
                                                            @if ($opcion->l_eliminar == 'S')
                                                                <a href="#" class="dropdown-item text-danger" data-toggle="modal" data-target="#formularioModal" 
                                                                v-on:click="Modal('modal-md', 'delete', data.id, data)"><i class="fas fa-window-close"></i> Eliminar</a>
                                                            @endif
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="{{asset('views/administrador/roles.js')}}"></script>
@endsection