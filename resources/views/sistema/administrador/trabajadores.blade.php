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
    </style>
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1><i class="fas fa-user-tie"></i> DRE COLABORADORES</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item active">DRE COLABORADORES</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row" style="padding-top: 10px;" id="trabajadores">
                <div class="col-12">
                    <div class="card card-dark card-outline">
                        <div class="card-header">
                            <h3 class="card-title">REGISTRO GENERAL DE COLABORADORES</h3>
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
                                            <h3>NUEVO COLABORADOR</h3>
                                        </div>
                                        <div class="card card-primary card-outline">
                                          <div class="m-2">
                                            <div class="form-row">
                                                <div class="col-md-12 obligatorio text-right" style="max-height: 15px;">
                                                    *<small style="vertical-align: top;"> Obligatorio</small>
                                                </div>

                                               <!-- <div class="form-group col-md-6">
                                                    <label for="nombre">NOMBRES Y APELLIDOS <span class="obligatorio">*</span></label>
                                                    <input type="text" id="nombre" v-model="trabajador.nombre" class="form-control" :class="[errors.nombre ? 'border-error' : '']">
                                                    <div class="input-error" v-if="errors.nombre">@{{ errors.nombre[0] }}</div>
                                                </div>-->

                                                <div class="form-group col-md-12">
                                                    <label for="persona">PERSONA <span class="obligatorio">*</span></label>
                                                    <div class="select-dropdown">
                                                        <button type="button" class="btn-dropdown form-control form-control text-left" data-toggle="dropdown" @click="Autofocus('persona_search')">@{{trabajador.persona_text}} <span><i class="fas fa-angle-down"></i></span></button>
                                                        <div class="dropdown-menu dropdown-sm lista-li-select2" id="myDropdownp" style="bottom: 0 !important; height: 300px; min-width: 98%;">
                                                            <div class="input-search">
                                                                <span><i class="fas fa-search"></i></span>
                                                                <input type="text" id="persona_search" class="form-control form-control-sm rounded-0" onkeyup="myFunctionp()" autocomplete="off" >
                                                            </div>
                                                            <a class="dropdown-item" v-for="persona in personas" @click="SelecPersona(persona)" :class="[(trabajador.persona_id == persona.id) ? 'active' : '']">@{{persona.c_dni+': '+persona.x_nombre}}</a>
                                                        </div>
                                                    </div>
                                                    <div class="input-error" v-if="errors.persona">@{{ errors.persona[0] }}</div>
                                                </div>

                                                <div class="col-auto form-group col-md-6">
                                                    <label  for="tipopersonal">TIPO PERSONAL <span class="obligatorio">*</span></label>
                                                        <select  id="tipopersonal" v-model="trabajador.tipopersonal" class="form-control" :class="[errors.tipopersonal ? 'border-error' : '']">
                                                            <option selected>NOMBRADO</option>
                                                            <option value="CONTRATADO">CONTRATADO CAS</option>
                                                            <option value="DESTACADO">DESTACADO</option>
                                                            <option value="ENCARGADO">ENCARGADO</option>
                                                            <option value="DESIGNADO">DESIGNADO</option>
                                                            <option value="LOCACIÓN DE SERVICIO">LOCACIÓN DE SERVICIO</option>
                                                        </select>
                                                        <div class="input-error" v-if="errors.personal">@{{ errors.tipopersonal[0] }}</div>
                                                </div>
                                                 <!--<div class="form-group col-md-4">
                                                    <label for="dni">DNI <span class="obligatorio">*</span></label>
                                                    <input type="text" id="dni" v-model="trabajador.dni" class="form-control" :class="[errors.dni ? 'border-error' : '']" maxlength="8" onkeypress="Numero()" autocomplete="off">
                                                    <div class="input-error" v-if="errors.dni">@{{ errors.dni[0] }}</div>
                                                </div>
                                               
                                                <div class="form-group col-md-4">
                                                    <label for="telefono">TELEFONO PERSONAL<span class="obligatorio">*</span></label>
                                                    <input type="text" id="telefono" v-model="trabajador.telefono" class="form-control" :class="[errors.telefono ? 'border-error' : '']" maxlength="9" onkeypress="Numero()">
                                                    <div class="input-error" v-if="errors.telefono">@{{ errors.telefono[0] }}</div>
                                                </div>-->
                                                <div class="form-group col-md-6">
                                                    <label for="unidad">UNIDAD <span class="obligatorio">*</span></label>
                                                    <select id="unidad" v-model="trabajador.unidad" class="form-control" :class="[errors.unidad ? 'border-error' : '']">
                                                        <option value="">--- Seleccione Opción ---</option>
                                                        @foreach ($unidades as $unidad)
                                                            <option value="{{$unidad->id}}">{{$unidad->id.': '.$unidad->x_unidades}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-error" v-if="errors.unidad">@{{ errors.unidad[0] }}</div>
                                                </div>
                                                
                                                
                                                <div class="form-group col-md-6">
                                                    <label for="direccion">DIRECCION <span class="obligatorio">*</span></label>
                                                    <select id="direccion" v-model="trabajador.direccion" class="form-control" :class="[errors.direccion ? 'border-error' : '']" @change="buscararea">
                                                        <option value="">--- Seleccione Opción ---</option>
                                                        @foreach ($direcciones as $direccion)
                                                            <option value="{{$direccion->id}}">{{$direccion->id.': '.$direccion->x_direcciones}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-error" v-if="errors.direccion">@{{ errors.direccion[0] }}</div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="area">AREA <span class="obligatorio">*</span></label>
                                                    <select id="area" v-model="trabajador.area" class="form-control" :class="[errors.area ? 'border-error' : '']">
                                                        <option value="">--- Seleccione Opción ---</option>
                                                        <option v-for="area in areas" :value="area.id">@{{area.id+': '+area.x_areas}}</option>
                                                    </select>
                                                    <div class="input-error" v-if="errors.area">@{{ errors.area[0] }}</div>
                                                </div>
                                                
                                                
                                                <div class="form-group col-md-12">
                                                    <label for="cargo">CARGO FUNCIONAL <span class="obligatorio">*</span></label>
                                                    <select id="cargo" v-model="trabajador.cargo" class="form-control" :class="[errors.cargo ? 'border-error' : '']">
                                                        <option value="">--- Seleccione Opción ---</option>
                                                        @foreach ($cargos as $cargo)
                                                            <option value="{{$cargo->id}}">{{$cargo->id.': '.$cargo->x_cargos}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-error" v-if="errors.cargo">@{{ errors.cargo[0] }}</div>
                                                </div>
                                                 <!--<div class="col-auto form-group col-md-6">
                                                    <label class="visually-hidden" for="autoSizingInputGroup" for="email">EMAIL PERSONAL <span class="obligatorio">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-text">@</div>
                                                        <input type="email" id="email" v-model="trabajador.email" class="form-control" :class="[errors.email ? 'border-error' : '']">
                                                        <div class="input-error" v-if="errors.email">@{{ errors.email[0] }}</div>
                                                    </div>
                                                </div>-->
                                                <div class="form-group col-md-6">
                                                    <label for="telefono2">TELEFONO TRABAJO</label>
                                                    <input type="text" id="telefono2" v-model="trabajador.telefono2" class="form-control" :class="[errors.telefono2 ? 'border-error' : '']" maxlength="9" onkeypress="Numero()">
                                                    <div class="input-error" v-if="errors.telefono2">@{{ errors.telefono2[0] }}</div>
                                                </div>

                                                <div class="col-auto form-group col-md-6">
                                                    <label class="visually-hidden" for="autoSizingInputGroup" for="email2">EMAIL COORPORATIVO</label>
                                                    <div class="input-group">
                                                        <div class="input-group-text">@</div>
                                                        <input type="email2" id="email2" v-model="trabajador.email2" class="form-control" :class="[errors.email2 ? 'border-error' : '']">
                                                        <div class="input-error" v-if="errors.email2">@{{ errors.email2[0] }}</div>
                                               
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
                                            <h3>EDITAR COLABORADOR</h3>
                                        </div>
                                        <div class="card card-warning card-outline">
                                            <div class="m-2">
                                                <div class="form-row">
                                                    <div class="col-md-12 obligatorio text-right" style="max-height: 15px;">
                                                        *<small style="vertical-align: top;"> Obligatorio</small>
                                                    </div>

                                                    <!--<div class="form-group col-md-6">
                                                        <label for="nombre">NOMBRES Y APELLIDOS <span class="obligatorio">*</span></label>
                                                        <input type="text" id="nombre" v-model="trabajador.nombre" class="form-control" :class="[errors.nombre ? 'border-error' : '']">
                                                        <div class="input-error" v-if="errors.nombre">@{{ errors.nombre[0] }}</div>
                                                    </div>-->
                                                    <div class="form-group col-md-12">
                                                        <label for="persona">PERSONA <span class="obligatorio">*</span></label>
                                                        <div class="select-dropdown">
                                                            <button type="button" class="btn-dropdown form-control form-control text-left" data-toggle="dropdown" @click="Autofocus('persona_search')">@{{trabajador.persona_text}} <span><i class="fas fa-angle-down"></i></span></button>
                                                            <div class="dropdown-menu dropdown-sm lista-li-select2" id="myDropdownp" style="bottom: 0 !important; height: 300px; min-width: 98%;">
                                                                <div class="input-search">
                                                                    <span><i class="fas fa-search"></i></span>
                                                                    <input type="text" id="persona_search" class="form-control form-control-sm rounded-0" onkeyup="myFunctionp()" autocomplete="off" >
                                                                </div>
                                                                <a class="dropdown-item" v-for="persona in personas" @click="SelecPersona(persona)" :class="[(trabajador.persona_id == persona.id) ? 'active' : '']">@{{persona.c_dni+': '+persona.x_nombre}}</a>
                                                            </div>
                                                        </div>
                                                        <div class="input-error" v-if="errors.persona">@{{ errors.persona[0] }}</div>
                                                    </div>

                                                    <div class="col-auto form-group col-md-6">
                                                        <label  for="tipopersonal">TIPO PERSONAL <span class="obligatorio">*</span></label>
                                                            <select  id="tipopersonal" v-model="trabajador.tipopersonal" class="form-control" :class="[errors.tipopersonal ? 'border-error' : '']">
                                                                <option selected>NOMBRADO</option>
                                                                <option value="CONTRATADO">CONTRATADO CAS</option>
                                                                <option value="DESTACADO">DESTACADO</option>
                                                                <option value="ENCARGADO">ENCARGADO</option>
                                                                <option value="DESIGNADO">DESIGNADO</option>
                                                                <option value="LOCACIÓN DE SERVICIO">LOCACIÓN DE SERVICIO</option>
                                                            </select>
                                                            <div class="input-error" v-if="errors.personal">@{{ errors.tipopersonal[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="unidad">UNIDAD <span class="obligatorio">*</span></label>
                                                        <select id="unidad" v-model="trabajador.unidad" class="form-control" :class="[errors.unidad ? 'border-error' : '']">
                                                            <option value="">--- Seleccione Opción ---</option>
                                                            @foreach ($unidades as $unidad)
                                                                <option value="{{$unidad->id}}">{{$unidad->id.': '.$unidad->x_unidades}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-error" v-if="errors.unidad">@{{ errors.unidad[0] }}</div>
                                                    </div>
                                                    <!--<div class="form-group col-md-4">
                                                        <label for="dni">DNI <span class="obligatorio">*</span></label>
                                                        <input type="text" id="dni" v-model="trabajador.dni" class="form-control" :class="[errors.dni ? 'border-error' : '']" maxlength="8" onkeypress="Numero()" autocomplete="off">
                                                        <div class="input-error" v-if="errors.dni">@{{ errors.dni[0] }}</div>
                                                    </div>
                                                
                                                    <div class="form-group col-md-4">
                                                        <label for="telefono">TELEFONO PERSONAL<span class="obligatorio">*</span></label>
                                                        <input type="text" id="telefono" v-model="trabajador.telefono" class="form-control" :class="[errors.telefono ? 'border-error' : '']" maxlength="9" onkeypress="Numero()">
                                                        <div class="input-error" v-if="errors.telefono">@{{ errors.telefono[0] }}</div>
                                                    </div>-->
                                                    
                                                    <div class="form-group col-md-6">
                                                        <label for="direccion">DIRECCION <span class="obligatorio">*</span></label>
                                                        <select id="direccion" v-model="trabajador.direccion" class="form-control" :class="[errors.direccion ? 'border-error' : '']" @change="buscararea">
                                                            <option value="">--- Seleccione Opción ---</option>
                                                            @foreach ($direcciones as $direccion)
                                                                <option value="{{$direccion->id}}">{{$direccion->id.': '.$direccion->x_direcciones}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-error" v-if="errors.direccion">@{{ errors.direccion[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="area">AREA <span class="obligatorio">*</span></label>
                                                        <select id="area" v-model="trabajador.area" class="form-control" :class="[errors.area ? 'border-error' : '']">
                                                            <option value="">--- Seleccione Opción ---</option>
                                                            <option v-for="area in areas" :value="area.id">@{{area.id+': '+area.x_areas}}</option>
                                                        </select>
                                                        <div class="input-error" v-if="errors.area">@{{ errors.area[0] }}</div>
                                                    </div>
                                                    
                                                    <div class="form-group col-md-12">
                                                        <label for="cargo">CARGO FUNCIONAL <span class="obligatorio">*</span></label>
                                                        <select id="cargo" v-model="trabajador.cargo" class="form-control" :class="[errors.cargo ? 'border-error' : '']">
                                                            <option value="">--- Seleccione Opción ---</option>
                                                            @foreach ($cargos as $cargo)
                                                                <option value="{{$cargo->id}}">{{$cargo->id.': '.$cargo->x_cargos}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-error" v-if="errors.cargo">@{{ errors.cargo[0] }}</div>
                                                    </div>
                                                    <!--<div class="form-group col-md-6">
                                                        <label for="email">EMAIL PERSONAL <span class="obligatorio">*</span></label>
                                                        <input type="email" id="email" v-model="trabajador.email" class="form-control" :class="[errors.email ? 'border-error' : '']">
                                                        <div class="input-error" v-if="errors.email">@{{ errors.email[0] }}</div>
                                                    </div>-->
                                                    <div class="form-group col-md-6">
                                                        <label for="telefono2">TELEFONO TRABAJO</label>
                                                        <input type="text" id="telefono2" v-model="trabajador.telefono2" class="form-control" :class="[errors.telefono2 ? 'border-error' : '']" maxlength="9" onkeypress="Numero()">
                                                        <div class="input-error" v-if="errors.telefono2">@{{ errors.telefono2[0] }}</div>
                                                    </div>
                                                    
                                                    
                                                    <div class="col-auto form-group col-md-6">
                                                        <label class="visually-hidden" for="autoSizingInputGroup" for="email2">EMAIL COORPORATIVO</label>
                                                        <div class="input-group">
                                                            <div class="input-group-text">@</div>
                                                            <input type="email2" id="email2" v-model="trabajador.email2" class="form-control" :class="[errors.email2 ? 'border-error' : '']">
                                                            <div class="input-error" v-if="errors.email2">@{{ errors.email2[0] }}</div>
                                                   
                                                        </div>
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
                                            <h3>ELIMINAR COLABORADOR</h3>
                                        </div>
                                        <div class="card card-danger card-outline">
                                            <div class="m-2">
                                                <p class="text-center">¿ Realmente desea eliminar el colaborador: <br>
                                                    <strong>@{{trabajador.nombre}}</strong> ?</p>

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
                                    <button type="button" class="btn btn-primary" style="width: 100px;" data-toggle="modal" data-target="#formularioModal" @click="Modal('modal-lg', 'create', null, null)"><i class="fas fa-user-plus"></i> Nuevo</button>
                                    @endif
                                    <button type="button" class="btn btn-warning" style="width: 110px;" value="Imprimir" onClick="imprimir()"> <i class="fas fa-print"></i> Imprimir</button>
                                   <!-- <button type="button" class="btn btn-warning" style="width: 110px;" value="Imprimir" onClick="imprimir()"> <i class="fas fa-file-excel" ></i> Exportar</button>-->
                                </div>
                               
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-default btn-md dropdown-toggle" data-toggle="dropdown"><i class="fas fa-filter"></i> Filtrar por</button>
                                            <div class="dropdown-menu dropdown-sm">
                                                <a class="dropdown-item" href="#" :class="[(search.filter == 1) ? 'active' : '']" @click="Filtrar('Nombre del Trabajador', 1)">Trabajador</a>
                                                <a class="dropdown-item" href="#" :class="[(search.filter == 2) ? 'active' : '']" @click="Filtrar('Tipo de Cargo', 2)">Cargo</a>
                                                
                                            </div>
                                        </div>
                                      <template v-if="search.filter == 1">
                                        <input type="text" v-model="search.datos" class="form-control" :placeholder="search.text_filter" @keyup.enter="Buscar">
                                        <div class="input-group-append">
                                            <button type="button" class="btn bg-gradient-success" @click="Buscar"><i class="fas fa-search"></i> Buscar</button>
                                        </div>
                                      </template>
                                      <select v-model="search.datos" class="form-control" :placeholder="search.text_filter" @change="Buscar" v-else>
                                        <option value="">--- TODOS LOS CARGOS ---</option>
                                        @foreach ($cargos as $cargo)
                                            <option value="{{$cargo->id}}">{{$cargo->x_cargos}}</option>
                                        @endforeach
                                      </select>
                                      
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive" id="my_table" style="min-height: 140px;">
                                <table class="table table-lg table-hover">
                                    <thead>
                                        <tr style="background-color: #f2f2f2;">
                                            <th width="5%" class="text-center">#</th>
                                            <th width="20%"class="text-left">COLABORADOR</th>
                                             <!--<th width="8%" class="text-center">DNI</th>-->
                                            <th width="10%">DIRECCION</th>
                                            <th width="20%">AREA</th>
                                            <th width="10%">UNIDAD</th>
                                            <th width="17%">CARGO</th>
                                            <th width="8%">TIPO</th>
                                        <!--<th width="8%">TEL-P</th>-->
                                            <th width="8%">TEL-T</th>
                                        <!--<th width="10%">EMAIL P</th>-->
                                            <th width="14%">EMAIL C</th>
                                            <th width="8%" class="text-center">OPCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(data, index) in listRequest" :class="'active-'+data.l_activo">
                                            <td class="text-center">@{{(index + pagination.index + 1)}}</td>
                                            <td class="text-left">@{{data.get_persona.x_nombre}}</td>
                                            <!--<td class="text-center">@{{data.c_dni}}</td>-->
                                            <td class="text-left"> @{{data.get_direccion.x_direcciones}}</td>
                                            <td class="text-left"> @{{data.get_area.x_areas}}</td>
                                            <td class="text-left"> @{{data.get_unidad.x_unidades}}</td>
                                            <td class="text-left">@{{data.get_cargo.x_cargos}}</td>
                                            <td class="text-left">@{{data.x_tipopersonal}}</td>
                                            <!--<td class="text-left">@{{data.x_telefono}}</td>-->
                                            <td class="text-left">@{{data.x_telefono2}}</td>
                                            <!--<td class="text-left">@{{data.x_email}}</td>-->
                                            <td class="text-left">@{{data.x_email2}}</td>
                                           
                                           
                                            <td class="text-center">
                                                @if (($opcion->l_editar == 'S') || ($opcion->l_eliminar == 'S')|| ($opcion->l_ver == 'S'))
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
                                                            
                                                            @if ($opcion->l_ver == 'S')
                                                                <a href="#" class="dropdown-item text-info" data-toggle="modal" data-target="#formularioModal"
                                                                v-on:click="Modal('modal-md', 'loked', data.id, data)"><i class="far fa-address-card"></i> Ver</a>
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
     function imprimir()
        {
       
        window.print();
       
        }
    var my_cargos = {!! json_encode($cargos) !!};
    var my_areas = {!! json_encode($areas) !!};
    var my_personas = {!! json_encode($personas) !!};
</script>
    <script src="{{asset('views/administrador/trabajadores.js')}}"></script>
@endsection
