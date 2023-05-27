@extends('layouts.app')

@section('css')
    <style>
        .input-search {
            padding: 8px;
        }
        .input-search span {
            position: absolute;
            right: 17px;
            padding-top: 5px;
            color: #ababab;
        }
        .lista-li-select2 {
            overflow-y: auto;
            overflow-x: hidden;
            max-height: 300px;
            font-size: 14px;
        }
        .btn-dropdown {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        .btn-dropdown span {
            position: absolute;
            right: 14px;
        }
        .dropdown-item {
            cursor: context-menu;
        }
        .modelos {
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
                    <h1><i class="fas fa-table"></i> Tablas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item active">Marcas</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row" style="padding-top: 10px;" id="marcas">
                <div class="col-12">
                    <div class="card card-danger card-outline">
                        <div class="card-header">
                            <h3 class="card-title">LISTA DE MARCAS</h3>
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
                                            <h3>NUEVO MARCA</h3>
                                        </div>

                                        <div class="m-2">
                                            <div class="form-row">
                                                <div class="col-md-12 obligatorio text-right" style="max-height: 15px;">
                                                    *<small style="vertical-align: top;"> Obligatorio</small>
                                                </div>
    
                                                <div class="form-group col-md-12">
                                                    <label for="bien">BIEN <span class="obligatorio">*</span></label>
                                                    <div class="select-dropdown">
                                                        <button type="button" class="btn-dropdown form-control form-control text-left" data-toggle="dropdown" @click="Autofocus('bien_search')">@{{marca.bien_text}} <span><i class="fas fa-angle-down"></i></span></button>
                                                        <div class="dropdown-menu dropdown-sm lista-li-select2" id="myDropdown" style="bottom: 0 !important; height: 300px; min-width: 98%;">
                                                            <div class="input-search">
                                                                <span><i class="fas fa-search"></i></span>
                                                                <input type="text" id="bien_search" class="form-control form-control-sm rounded-0" onkeyup="myFunction()" autocomplete="off" >
                                                            </div>
                                                            <a class="dropdown-item" v-for="bien in bienes" @click="SelecBien(bien)" :class="[(marca.bien_id == bien.id) ? 'active' : '']">@{{bien.c_codigo+': '+bien.x_nombre}}</a>
                                                        </div>
                                                    </div>
                                                    <div class="input-error" v-if="errors.bien">@{{ errors.bien[0] }}</div>
                                               
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="nombre">NOMBRE DE LA MARCA <span class="obligatorio">*</span></label>
                                                    <input type="text" id="nombre" v-model="marca.nombre" class="form-control" :class="[errors.nombre ? 'border-error' : '']">
                                                    <div class="input-error" v-if="errors.nombre">@{{ errors.nombre[0] }}</div>
                                                </div>
    
                                                <div class="col-md-12 mt-3 mb-4">
                                                    <button class="btn btn-block bg-gradient-secondary" @click="Store('create')"><i class="fa fa-check-square"></i> Guardar registro</button>
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
                                            <h3>EDITAR MARCA</h3>
                                        </div>

                                        <div class="m-2">
                                            <div class="form-row">
                                                <div class="col-md-12 obligatorio text-right" style="max-height: 15px;">
                                                    *<small style="vertical-align: top;"> Obligatorio</small>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label for="bien">BIEN <span class="obligatorio">*</span></label>
                                                    <div class="select-dropdown">
                                                        <button type="button" class="btn-dropdown form-control form-control text-left" data-toggle="dropdown" @click="Autofocus('bien_search')">@{{marca.bien_text}} <span><i class="fas fa-angle-down"></i></span></button>
                                                        <div class="dropdown-menu dropdown-sm lista-li-select2" id="myDropdown" style="bottom: 0 !important; height: 300px; min-width: 98%;">
                                                            <div class="input-search">
                                                                <span><i class="fas fa-search"></i></span>
                                                                <input type="text" id="bien_search" class="form-control form-control-sm rounded-0" onkeyup="myFunction()" autocomplete="off" >
                                                            </div>
                                                            <a class="dropdown-item" v-for="bien in bienes" @click="SelecBien(bien)" :class="[(marca.bien_id == bien.id) ? 'active' : '']">@{{bien.c_codigo+': '+bien.x_nombre}}</a>
                                                        </div>
                                                    </div>
                                                    <div class="input-error" v-if="errors.bien">@{{ errors.bien[0] }}</div>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="nombre">NOMBRE DE LA MARCA <span class="obligatorio">*</span></label>
                                                    <input type="text" id="nombre" v-model="marca.nombre" class="form-control" :class="[errors.nombre ? 'border-error' : '']">
                                                    <div class="input-error" v-if="errors.nombre">@{{ errors.nombre[0] }}</div>
                                                </div>

                                                <div class="col-md-12 mt-3 mb-4">
                                                    <button class="btn btn-block btn-warning" @click="Update('edit')"><i class="fa fa-check-square"></i> Actualizar registro</button>
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
                                            <h3>ELIMINAR MARCA</h3>
                                        </div>

                                        <div class="m-2">
                                            <p class="text-center">¿ Realmente desea eliminar la Marca: <br>
                                                <strong>@{{marca.nombre}}</strong> ?</p>

                                            <div class="mt-4 mb-4 text-center">
                                                <button class="btn btn-danger" @click="Delete('delete')" style="width: 300px;"><i class="fa fa-check-square"></i> Eliminar registro</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- DELETE --}}

                                {{-- MODELOS --}}
                                <div class="modal-content" v-if="modal.method == 'modelo'" id="modelo">
                                    <div class="modal-body text-center" style="padding: 0.8rem 1.3rem">
                                        <button type="button" class="btn-close" data-dismiss="modal" @click="CloseModal"><i class="fas fa-times"></i></button>
                                        <div class="text-center mb-4 mt-4">
                                            <h3>MODELOS</h3>
                                        </div>

                                        <div class="m-2">
                                            <h4 class="text-center"><i class="fas fa-tag"></i> (Marca: @{{marca.nombre}})</h4>

                                            <button class="btn btn-sm btn-secondary float-left mb-3" @click="ModalModelo('create')"><i class="fas fa-plus"></i> Nuevo</button>
                                            <div class="table-reponsive mb-5">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr style="background-color: #f2f2f2;">
                                                            <th width="10%" class="text-center">#</th>
                                                            <th width="75%" class="text-left">NOMBRE DEL MODELO</th>
                                                            <th width="15%" class="text-center">OPCIONES</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr v-for="(mod, index) in modelos">
                                                            <td class="text-center">@{{index+1}}</td>
                                                            <td class="text-left">@{{mod.x_nombre}}</td>
                                                            <td class="text-center p-0">
                                                                <a href="#" class="text-warning mr-1" style="font-size: 24px;" @click="ModalModelo('edit', mod)"><i class="fas fa-pen-square"></i></a>
                                                                <a href="#" class="text-danger" style="font-size: 24px;" @click="ModalModelo('delete', mod)"><i class="fas fa-window-close"></i></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                {{-- MODELOS --}}
                            </div>
                        </div>
                        <!-- MODAL -->

                        <!-- MODAL FORM MODELOS -->
                        <div class="modal fade" id="modeloModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="background-color: #00000082;">
                            <div class="modal-dialog modal-md" role="document">
                                <div class="modal-content" id="form_modelo">

                                    <div class="modal-body" style="padding: 0.8rem 1.3rem">
                                        <button type="button" class="btn-close" data-dismiss="modal" @click="CloseModalModelo"><i class="fas fa-times"></i></button>
                                        <div class="text-center mb-4 mt-4">
                                            <h3>@{{modelo.titulo}} MODELO</h3>
                                        </div>

                                        <div class="m-2">
                                            <div class="form-row"  v-if="(modelo.metodo == 'create') || (modelo.metodo == 'edit')">
                                                <div class="col-md-12 obligatorio text-right" style="max-height: 15px;">
                                                    *<small style="vertical-align: top;"> Obligatorio</small>
                                                </div>
    
                                                <div class="form-group col-md-12">
                                                    <label for="nombre">NOMBRE DEL MODELO <span class="obligatorio">*</span></label>
                                                    <input type="text" id="nombre" v-model="modelo.nombre" class="form-control" :class="[errors.nombre ? 'border-error' : '']">
                                                    <div class="input-error" v-if="errors.nombre">@{{ errors.nombre[0] }}</div>
                                                </div>
    
                                                <div class="col-md-12 mt-3 mb-4">
                                                    <button class="btn btn-block bg-gradient-secondary" @click="StoreModelo('form_modelo')" v-if="modelo.metodo == 'create'"><i class="fa fa-check-square"></i> Guardar registro</button>
                                                    <button class="btn btn-block bg-gradient-warning" @click="EditModelo('form_modelo')" v-if="modelo.metodo == 'edit'"><i class="fa fa-check-square"></i> Actualizar registro</button>
                                                </div>
                                            </div>

                                            <div v-else>
                                                <p class="text-center">¿ Realmente desea eliminar la Modelo: <br>
                                                    <strong>@{{modelo.nombre}}</strong> ?</p>
    
                                                <div class="mt-4 mb-4 text-center">
                                                    <button class="btn btn-danger" @click="DeleteModelo('form_modelo')" style="width: 300px;"><i class="fa fa-check-square"></i> Eliminar registro</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- MODAL FORM MODELOS -->

                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-lg-8">
                                    @if ($opcion->l_crear == 'S')
                                    <button type="button" class="btn bg-gradient-secondary" style="width: 100px;" data-toggle="modal" data-target="#formularioModal" @click="Modal('modal-md', 'create', null, null)"><i class="fas fa-plus"></i> Nuevo</button>
                                    @endif
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-default btn-md dropdown-toggle" data-toggle="dropdown"><i class="fas fa-filter"></i> Filtrar por</button>
                                            <div class="dropdown-menu dropdown-sm">
                                                <a class="dropdown-item" href="#" :class="[(search.filter == 1) ? 'active' : '']" @click="Filtrar('Nombre de la Marca', 1)">Nombre</a>
                                                <a class="dropdown-item" href="#" :class="[(search.filter == 2) ? 'active' : '']" @click="Filtrar('Tipo de Bien', 2)">Bien</a>
                                            </div>
                                        </div>
                                        <template v-if="search.filter == 1">
                                            <input type="text" v-model="search.datos" class="form-control" :placeholder="search.text_filter" @keyup.enter="Buscar">
                                            <div class="input-group-append">
                                                <button type="button" class="btn bg-gradient-secondary" @click="Buscar"><i class="fas fa-search"></i> Buscar</button>
                                            </div>
                                        </template>
                                        <select v-model="search.datos" class="form-control" :placeholder="search.text_filter" @change="Buscar" v-else>
                                            <option value="">--- Todos los Bienes ---</option>
                                            @foreach ($bienes as $bien)
                                                <option value="{{$bien->id}}">{{$bien->c_codigo.': '.$bien->x_nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive" id="my_table" style="min-height: 140px;">
                                <table class="table table-md table-hover">
                                    <thead>
                                        <tr style="background-color: #f2f2f2;">
                                            <th width="5%" class="text-center">#</th>
                                            <th width="30%">NOMBRE DE LA MARCA</th>
                                            <th width="20%">MODELOS</th>
                                            <th width="35%">BIEN</th>
                                            <th width="10%" class="text-center">OPCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(data, index) in listRequest" :class="'active-'+data.l_activo">
                                            <td class="text-center">@{{(index + pagination.index + 1)}}</td>
                                            <td>
                                                <a href="#" data-toggle="modal" data-target="#formularioModal"
                                                v-on:click="Modal('modal-lg', 'modelo', data.id, data)">
                                                    <i class="fas fa-chevron-right"></i> @{{data.x_nombre}}
                                                </a>
                                            </td>
                                            <td class="project_progress">
                                                <span class="modelos">
                                                    <a href="#" data-toggle="modal" data-target="#formularioModal" style="color: #000;" title="Lista de modelos"
                                                    v-on:click="Modal('modal-lg', 'modelo', data.id, data)"><i class="fas fa-shapes"></i></a>
                                                </span>
                                                <div style="margin-left: 30px;">
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-green" role="progressbar" :aria-valuenow="data.get_modelos_count" aria-valuemin="0" aria-valuemax="100" :style="'width:'+data.get_modelos_count+'%'">
                                                        </div>
                                                    </div>
                                                    <small>
                                                        @{{data.get_modelos_count}} Modelos
                                                    </small>
                                                </div>
                                            </td>
                                            <td>@{{data.get_bien.c_codigo+': '+data.get_bien.x_nombre}}</td>
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
    <script>
        var my_bienes = {!! json_encode($bienes) !!};
    </script>
    <script src="{{asset('views/tablas/marcas.js')}}"></script>
@endsection