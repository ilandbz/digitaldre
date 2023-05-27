@extends('layouts.app')

@section('css')
    <style>
        .dependencia {
            position: absolute;
            font-size: 20px;
        }
    </style>
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1><i class="far fa-address-card"></i> PERSONAS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item active">PERSONAS</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row" style="padding-top: 10px;" id="personas">
                <div class="col-12">
                    <div class="card card-dark card-outline">
                        <div class="card-header">
                            <h3 class="card-title">LISTA DE PERSONAS</h3>
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
                                            <h3>NUEVA PERSONA</h3>
                                        </div>
                                        <div class="card card-primary card-outline">
                                            <div class="m-2">
                                                <div class="form-row">
                                                    <div class="col-md-12 obligatorio text-right" style="max-height: 15px;">
                                                        *<small style="vertical-align: top;"> Campo Obligatorio</small>
                                                    </div>
        
                                                    <div class="form-group col-md-12">
                                                        <label for="nombre">PERSONA <span class="obligatorio">*</span></label>
                                                        <input type="text" id="nombre" v-model="persona.nombre" class="form-control" :class="[errors.nombre ? 'border-error' : '']" >
                                                        <div class="input-error" v-if="errors.nombre">@{{ errors.nombre[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="dni">DNI <span class="obligatorio">*</span></label>
                                                        <input type="text" id="dni" v-model="persona.dni" class="form-control" :class="[errors.dni ? 'border-error' : '']" maxlength="8" onkeypress="Numero()" autocomplete="off">
                                                        <div class="input-error" v-if="errors.dni">@{{ errors.dni[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="telefono">TELEFONO <span class="obligatorio">*</span></label>
                                                        <input type="text" id="telefono" v-model="persona.telefono" class="form-control" :class="[errors.telefono ? 'border-error' : '']" maxlength="9" onkeypress="Numero()">
                                                        <div class="input-error" v-if="errors.telefono">@{{ errors.telefono[0] }}</div>
                                                    </div>
                                                    <div class="col-auto form-group col-md-12">
                                                        <label class="visually-hidden" for="autoSizingInputGroup" for="email">EMAIL PERSONAL</label>
                                                        <div class="input-group">
                                                            <div class="input-group-text">@</div>
                                                            <input type="email" id="email" v-model="persona.email" class="form-control" :class="[errors.email ? 'border-error' : '']">
                                                            <div class="input-error" v-if="errors.email">@{{ errors.email[0] }}</div>
                                                   
                                                        </div>
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
                                            <h3>EDITAR PERSONA</h3>
                                        </div>
                                        <div class="card card-warning card-outline">
                                            <div class="m-2">
                                                <div class="form-row">
                                                    <div class="col-md-12 obligatorio text-right" style="max-height: 15px;">
                                                        *<small style="vertical-align: top;"> Campo Obligatorio</small>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label for="nombre">PERSONA <span class="obligatorio">*</span></label>
                                                        <input type="text" id="nombre" v-model="persona.nombre" class="form-control" :class="[errors.nombre ? 'border-error' : '']" >
                                                        <div class="input-error" v-if="errors.nombre">@{{ errors.nombre[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="dni">DNI <span class="obligatorio">*</span></label>
                                                        <input type="text" id="dni" v-model="persona.dni" class="form-control" :class="[errors.dni ? 'border-error' : '']" maxlength="8" onkeypress="Numero()" autocomplete="off">
                                                        <div class="input-error" v-if="errors.dni">@{{ errors.dni[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="telefono">TELEFONO <span class="obligatorio">*</span></label>
                                                        <input type="text" id="telefono" v-model="persona.telefono" class="form-control" :class="[errors.telefono ? 'border-error' : '']" maxlength="9" onkeypress="Numero()">
                                                        <div class="input-error" v-if="errors.telefono">@{{ errors.telefono[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="email">EMAIL PERSONA <span class="obligatorio">*</span></label>
                                                        <input type="email" id="email" v-model="persona.email" class="form-control" :class="[errors.email ? 'border-error' : '']">
                                                        <div class="input-error" v-if="errors.email">@{{ errors.email[0] }}</div>
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
                                            <h3>ELIMINAR PERSONA</h3>
                                        </div>
                                        <div class="card card-danger card-outline">
                                            <div class="m-2">
                                                <p class="text-center">¿ Realmente desea eliminar la Persona: <br>
                                                    <strong>@{{persona.nombre}}</strong> ?</p>

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
                                    <button type="button" class="btn bg-gradient-primary" style="width: 100px;" data-toggle="modal" data-target="#formularioModal" @click="Modal('modal-md', 'create', null, null)"><i class="fas fa-plus"></i> Nuevo</button>
                                    @endif
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-default btn-md dropdown-toggle" data-toggle="dropdown"><i class="fas fa-filter"></i> Filtrar por</button>
                                            <div class="dropdown-menu dropdown-sm">
                                                <a class="dropdown-item" href="#" :class="[(search.filter == 1) ? 'active' : '']" @click="Filtrar('Buscar por Persona', 1)">Persona-DNI</a>
                                                
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
                                            <th width="5%" class="text-center">#</th>
                                            <th width="40%" class="text-left">PERSONA</th>
                                            <th width="15%" class="text-left">DNI</th>
                                            <th width="15%" class="text-center">TELEFONO</th>
                                            <th width="25%" class="text-center">EMAIL</th>
                                            <th width="15%" class="text-center">OPCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(data, index) in listRequest" :class="'active-'+data.l_activo">
                                            <td class="text-center">@{{(index + pagination.index + 1)}}</td>
                                            <td class="text-left"><strong><i class="fas fa-user-alt"></i> </strong> @{{data.x_nombre}}</td>
                                            <td class="text-left"><strong><i class="fas fa-id-card"></i> </strong> @{{data.c_dni}}</td>
                                            <td class="text-left"><strong><i class="fas fa-mobile-alt"></i> </strong> @{{data.x_telefono}}</td>
                                            <td class="text-left"><strong><i class="fas fa-envelope"></i> </strong> @{{data.x_email}}</td>
                                            <td class="text-center">
                                                @if (($opcion->l_editar == 'S') || ($opcion->l_eliminar == 'S'))
                                                    <div class="">
                                                        <button type="button" class="btn btn-default pt-0 pb-0" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                                        <div class="dropdown-menu">
                                                            @if ($opcion->l_editar == 'S')
                                                                <a href="#" class="dropdown-item text-warning" data-toggle="modal" data-target="#formularioModal" 
                                                                v-on:click="Modal('modal-md', 'edit', data.id, data)"><i class="fas fa-pen-square"></i> Editar</a>
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
    <script src="{{asset('views/administrador/personas.js')}}"></script>
@endsection