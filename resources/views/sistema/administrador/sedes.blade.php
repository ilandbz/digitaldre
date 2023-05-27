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
                    <h1><i class="fas fa-map-marked-alt"></i> SEDES</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item active">SEDES</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row" style="padding-top: 10px;" id="sedes">
                <div class="col-12">
                    <div class="card card-dark card-outline">
                        <div class="card-header">
                            <h3 class="card-title">LISTA DE SEDES</h3>
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
                                            <h3>NUEVA SEDE</h3>
                                        </div>
                                        <div class="card card-primary card-outline">
                                            <div class="m-2">
                                                <div class="form-row">
                                                    <div class="col-md-12 obligatorio text-right" style="max-height: 15px;">
                                                        *<small style="vertical-align: top;"> Obligatorio</small>
                                                    </div>
        
                                                    <div class="form-group col-md-12">
                                                        <label for="codigo">CÓDIGO <span class="obligatorio">*</span></label>
                                                        <input type="text" id="codigo" v-model="sede.codigo" class="form-control" :class="[errors.codigo ? 'border-error' : '']" maxlength="4" onkeypress="Numero()" autocomplete="off">
                                                        <div class="input-error" v-if="errors.codigo">@{{ errors.codigo[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="nombre">NOMBRE DE LA SEDE <span class="obligatorio">*</span></label>
                                                        <input type="text" id="nombre" v-model="sede.nombre" class="form-control" :class="[errors.nombre ? 'border-error' : '']">
                                                        <div class="input-error" v-if="errors.nombre">@{{ errors.nombre[0] }}</div>
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
                                            <h3>EDITAR SEDE</h3>
                                        </div>
                                        <div class="card card-warning card-outline">
                                            <div class="m-2">
                                                <div class="form-row">
                                                    <div class="col-md-12 obligatorio text-right" style="max-height: 15px;">
                                                        *<small style="vertical-align: top;"> Obligatorio</small>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label for="codigo">CÓDIGO <span class="obligatorio">*</span></label>
                                                        <input type="text" id="codigo" v-model="sede.codigo" class="form-control" :class="[errors.codigo ? 'border-error' : '']" maxlength="4" onkeypress="Numero()" autocomplete="off">
                                                        <div class="input-error" v-if="errors.codigo">@{{ errors.codigo[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="nombre">NOMBRE DE LA SEDE <span class="obligatorio">*</span></label>
                                                        <input type="text" id="nombre" v-model="sede.nombre" class="form-control" :class="[errors.nombre ? 'border-error' : '']">
                                                        <div class="input-error" v-if="errors.nombre">@{{ errors.nombre[0] }}</div>
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
                                            <h3>ELIMINAR SEDE</h3>
                                        </div>
                                        <div class="card card-danger card-outline">
                                            <div class="m-2">
                                                <p class="text-center">¿ Realmente desea eliminar la sede: <br>
                                                    <strong>@{{sede.nombre}}</strong> ?</p>

                                                <div class="mt-4 mb-4 text-center">
                                                    <button class="btn btn-danger" @click="Delete('delete')" style="width: 300px;"><i class="fa fa-check-square"></i> Eliminar registro</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- DELETE --}}
                                
                                {{-- DEPENDENCIAS --}}
                                <div class="modal-content" v-if="modal.method == 'dependencia'" id="dependencia">
                                    <div class="modal-body text-center" style="padding: 0.8rem 1.3rem">
                                        <button type="button" class="btn-close" data-dismiss="modal" @click="CloseModal"><i class="fas fa-times"></i></button>
                                        <div class="text-center mb-4 mt-4">
                                            <h3>DEPENDENCIAS</h3>
                                        </div>

                                        <div class="m-2">
                                            <p class="text-center"><i class="fas fa-map-marked-alt"></i> Sede: @{{sede.nombre}}</p>

                                            <button class="btn btn-sm btn-secondary float-left mb-3" @click="ModalDependencia('create')"><i class="fas fa-plus"></i> Nuevo</button>
                                            <div class="table-reponsive mb-5">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr style="background-color: #f2f2f2;">
                                                            <th width="7%" class="text-center">#</th>
                                                            <th width="10%" class="text-center">CÓDIGO</th>
                                                            <th width="68%" class="text-left">NOMBRE DE LA DEPENDENCIA</th>
                                                            <th width="15%" class="text-center">OPCIONES</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr v-for="(dep, index) in dependencias">
                                                            <td class="text-center">@{{index+1}}</td>
                                                            <td class="text-center">@{{dep.c_codigo}}</td>
                                                            <td class="text-left">@{{dep.x_nombre}}</td>
                                                            <td class="text-center p-0">
                                                                <a href="#" class="text-warning mr-1" style="font-size: 24px;" @click="ModalDependencia('edit', dep)"><i class="fas fa-pen-square"></i></a>
                                                                <a href="#" class="text-danger" style="font-size: 24px;" @click="ModalDependencia('delete', dep)"><i class="fas fa-window-close"></i></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                {{-- DEPENDENCIAS --}}
                            </div>
                        </div>
                        <!-- MODAL -->

                        <!-- MODAL FORM DEPENDENCIA -->
                        <div class="modal fade" id="dependenciaModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="background-color: #00000082;">
                            <div class="modal-dialog modal-md" role="document">
                                <div class="modal-content" id="form_dependencia">

                                    <div class="modal-body" style="padding: 0.8rem 1.3rem">
                                        <button type="button" class="btn-close" data-dismiss="modal" @click="CloseModalDependencia"><i class="fas fa-times"></i></button>
                                        <div class="text-center mb-4 mt-4">
                                            <h3>@{{dependencia.titulo}} DEPENDENCIA</h3>
                                        </div>

                                        <div class="m-2">
                                            <div class="form-row"  v-if="(dependencia.metodo == 'create') || (dependencia.metodo == 'edit')">
                                                <div class="col-md-12 obligatorio text-right" style="max-height: 15px;">
                                                    *<small style="vertical-align: top;"> Obligatorio</small>
                                                </div>
    
                                                <div class="form-group col-md-12">
                                                    <label for="codigo">CÓDIGO <span class="obligatorio">*</span></label>
                                                    <input type="text" id="codigo" v-model="dependencia.codigo" class="form-control" :class="[errors.codigo ? 'border-error' : '']" maxlength="6" onkeypress="Numero()" autocomplete="off" :placeholder="sede.codigo+'--'">
                                                    <div class="input-error" v-if="errors.codigo">@{{ errors.codigo[0] }}</div>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="nombre">NOMBRE DE LA DEPENDENCIA <span class="obligatorio">*</span></label>
                                                    <input type="text" id="nombre" v-model="dependencia.nombre" class="form-control" :class="[errors.nombre ? 'border-error' : '']">
                                                    <div class="input-error" v-if="errors.nombre">@{{ errors.nombre[0] }}</div>
                                                </div>
    
                                                <div class="col-md-12 mt-3 mb-4">
                                                    <button class="btn btn-block bg-gradient-secondary" @click="StoreDependencia('form_dependencia')" v-if="dependencia.metodo == 'create'"><i class="fa fa-check-square"></i> Guardar registro</button>
                                                    <button class="btn btn-block bg-gradient-warning" @click="EditDependencia('form_dependencia')" v-if="dependencia.metodo == 'edit'"><i class="fa fa-check-square"></i> Actualizar registro</button>
                                                </div>
                                            </div>

                                            <div v-else>
                                                <p class="text-center">¿ Realmente desea eliminar la Dependencia: <br>
                                                    <strong>@{{dependencia.nombre}}</strong> ?</p>
    
                                                <div class="mt-4 mb-4 text-center">
                                                    <button class="btn btn-danger" @click="DeleteDependencia('form_dependencia')" style="width: 300px;"><i class="fa fa-check-square"></i> Eliminar registro</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- MODAL FORM DEPENDENCIA -->

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
                                            <th width="8%" class="text-center">CÓDIGO</th>
                                            <th width="60%">NOMBRE DE LA SEDE</th>
                                            <th width="17%">DEPENDENCIAS</th>
                                            <th width="10%" class="text-center">OPCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(data, index) in listRequest" :class="'active-'+data.l_activo">
                                            <td class="text-center">@{{(index + pagination.index + 1)}}</td>
                                            <td class="text-center">@{{data.c_codigo}}</td>
                                            <td>
                                                <a href="#" data-toggle="modal" data-target="#formularioModal"
                                                v-on:click="Modal('modal-lg', 'dependencia', data.id, data)">
                                                    <i class="fas fa-chevron-right"></i> @{{data.x_nombre}}
                                                </a>
                                            </td>
                                            <td class="project_progress">
                                                <span class="dependencia">
                                                    <a href="#" data-toggle="modal" data-target="#formularioModal" style="color: #000;" title="Lista de Dependencias"
                                                    v-on:click="Modal('modal-lg', 'dependencia', data.id, data)"><i class="fas fa-landmark"></i></a>
                                                </span>
                                                <div style="margin-left: 30px;">
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-green" role="progressbar" :aria-valuenow="data.get_dependencias_count" aria-valuemin="0" aria-valuemax="100" :style="'width:'+data.get_dependencias_count+'%'">
                                                        </div>
                                                    </div>
                                                    <small>
                                                        @{{data.get_dependencias_count}} Dependencias
                                                    </small>
                                                </div>
                                            </td>
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
    <script src="{{asset('views/administrador/sedes.js')}}"></script>
@endsection