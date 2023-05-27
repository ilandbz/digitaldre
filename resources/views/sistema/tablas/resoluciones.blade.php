@extends('layouts.app')

@section('css')
    <style>
       .dependencia {
            position: absolute;
            font-size: 20px;
        }
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
                    <h1><i class="far fa-file-alt"></i> RESOLUCIONES</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item active">RESOLUCIONES</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row" style="padding-top: 10px;" id="resoluciones">
                <div class="col-12">
                    <div class="card card-success card-outline">
                        <div class="card-header" >
                            <i style="color:#04AA6D"><h3 class="card-title">REGISTRO GENERAL DE RESOLUCIONES</h3></i>
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
                                            <h3>NUEVA RESOLUCION</h3>
                                        </div>
                                        <div class="card card-primary card-outline">
                                          <div class="m-2">
                                            <div class="form-row">
                                                <div class="col-md-12 obligatorio text-right" style="max-height: 15px;">
                                                    *<small style="vertical-align: top;"> Obligatorio</small>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="numero">RESOLUCION N°<span class="obligatorio">*</span></label>
                                                    <input type="text" id="numero" v-model="resolucion.numero" class="form-control" :class="[errors.numero ? 'border-error' : '']" maxlength="15" onkeypress="Numero()">
                                                    <div class="input-error" v-if="errors.numero">@{{ errors.numero[0] }}</div>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="fecha">FECHA<span class="obligatorio">*</span></label>
                                                    <input type="date" id="fecha" name="trip-start" v-model="resolucion.fecha" class="form-control" :class="[errors.fecha ? 'border-error' : '']">
                                                    <div class="input-error" v-if="errors.fecha">@{{ errors.fecha[0] }}</div>
                                                </div>
                                                <div class="col-auto form-group col-md-4">
                                                    <label  for="estado">ESTADO <span class="obligatorio">*</span></label>
                                                        <select  id="estado" v-model="resolucion.estado" class="form-control" :class="[errors.estado ? 'border-error' : '']">
                                                            <option selected>PUBLICO</option>
                                                            <option value="CONFIDENCIAL">CONFIDENCIAL</option>
                                                            <option value="PUBLICO GENERAL">PUBLICO GENERAL</option>
                                                        </select>
                                                        <div class="input-error" v-if="errors.estado">@{{ errors.estado[0] }}</div>
                                                </div>
                                             
                                                <div class="form-group col-md-6">
                                                    <label for="resoluciontipo">TIPO RE RESOLUCIÓN <span class="obligatorio">*</span></label>
                                                    <select id="resoluciontipo" v-model="resolucion.resoluciontipo" class="form-control" :class="[errors.resoluciontipo ? 'border-error' : '']">
                                                        <option value="">--- Seleccione Opción ---</option>
                                                        @foreach ($resoluciontipos as $resoluciontipo)
                                                            <option value="{{$resoluciontipo->id}}">{{$resoluciontipo->id.': '.$resoluciontipo->x_resoluciontipos}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-error" v-if="errors.resoluciontipo">@{{ errors.resoluciontipo[0] }}</div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                    <label for="dependencia">DEPENDENCIA-UGEL <span class="obligatorio">*</span></label>
                                                    <select id="dependencia" v-model="resolucion.dependencia" class="form-control" :class="[errors.dependencia ? 'border-error' : '']">
                                                        <option value="">--- Seleccione Opción ---</option>
                                                        @foreach ($dependencias as $dependencia)
                                                            <option value="{{$dependencia->id}}">{{$dependencia->id.': '.$dependencia->x_nombre}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-error" v-if="errors.dependencia">@{{ errors.dependencia[0] }}</div>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label for="visto">VISTO<span class="obligatorio">*</span></label> 
                                                    <textarea  id="visto" rows="3" v-model="resolucion.visto" class="form-control" :class="[errors.visto ? 'border-error' : '']" maxlength="300"></textarea>
                                                    <div class="input-error" v-if="errors.visto">@{{ errors.visto[0] }}</div>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="asunto">ASUNTO<span class="obligatorio">*</span></label> 
                                                    <textarea  id="asunto" rows="3" v-model="resolucion.asunto" class="form-control" :class="[errors.asunto ? 'border-error' : '']" maxlength="250"></textarea>
                                                    <div class="input-error" v-if="errors.asunto">@{{ errors.asunto[0] }}</div>
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label for="archivo">SUBIR ARCHIVO  * PDF<span class="obligatorio">*</span></label> 
                                                    <input class="form-control" type="file" id="archivo" @change="Archivo"  :class="[errors.archivo ? 'border-error' : '']" >
                                                    <div class="input-error" v-if="errors.archivo">@{{ errors.archivo[0] }}</div>     
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
                                            <h3>EDITAR RESOLUCION</h3>
                                        </div>
                                        <div class="card card-warning card-outline">
                                            <div class="m-2">
                                                <div class="form-row">
                                                    <div class="col-md-12 obligatorio text-right" style="max-height: 15px;">
                                                        *<small style="vertical-align: top;"> Obligatorio</small>
                                                    </div>

                                            
                                          <!--  <div class="form-group col-md-12">
                                                    <label for="persona">PERSONA <span class="obligatorio">*</span></label>
                                                    <div class="select-dropdown">
                                                        <button type="button" class="btn-dropdown form-control form-control text-left" data-toggle="dropdown" @click="Autofocus('persona_search')">@{{resolucion.persona_text}} <span><i class="fas fa-angle-down"></i></span></button>
                                                        <div class="dropdown-menu dropdown-sm lista-li-select2" id="myDropdownp" style="bottom: 0 !important; height: 300px; min-width: 98%;">
                                                            <div class="input-search">
                                                                <span><i class="fas fa-search"></i></span>
                                                                <input type="text" id="persona_search" class="form-control form-control-sm rounded-0" onkeyup="myFunctionp()" autocomplete="off" >
                                                            </div>
                                                            <a class="dropdown-item" v-for="persona in personas" @click="SelecPersona(persona)" :class="[(resolucion.persona_id == persona.id) ? 'active' : '']">@{{persona.c_dni+': '+persona.x_nombre}}</a>
                                                        </div>
                                                    </div>
                                                    <div class="input-error" v-if="errors.persona">@{{ errors.persona[0] }}</div>
                                                </div>-->

                                                <div class="form-group col-md-4">
                                                    <label for="numero">RESOLUCION N°<span class="obligatorio">*</span></label>
                                                    <input type="text" id="numero" v-model="resolucion.numero" class="form-control" :class="[errors.numero ? 'border-error' : '']" maxlength="15" onkeypress="Numero()">
                                                    <div class="input-error" v-if="errors.numero">@{{ errors.numero[0] }}</div>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="fecha">FECHA<span class="obligatorio">*</span></label>
                                                    <input type="date" id="fecha"  v-model="resolucion.fecha" class="form-control" :class="[errors.fecha ? 'border-error' : '']">
                                                    <div class="input-error" v-if="errors.fecha">@{{ errors.fecha[0] }}</div>
                                                </div>
                                                <div class="col-auto form-group col-md-4">
                                                    <label  for="estado">ESTADO <span class="obligatorio">*</span></label>
                                                        <select  id="estado" v-model="resolucion.estado" class="form-control" :class="[errors.estado ? 'border-error' : '']">
                                                            <option selected>PUBLICO</option>
                                                            <option value="CONFIDENCIAL">CONFIDENCIAL</option>
                                                            <option value="PUBLICO GENERAL">PUBLICO GENERAL</option>
                                                        </select>
                                                        <div class="input-error" v-if="errors.estado">@{{ errors.estado[0] }}</div>
                                                </div>
                                             
                                                <div class="form-group col-md-6">
                                                    <label for="resoluciontipo">TIPO RE RESOLUCIÓN<span class="obligatorio">*</span></label>
                                                    <select id="resoluciontipo" v-model="resolucion.resoluciontipo" class="form-control" :class="[errors.resoluciontipo ? 'border-error' : '']">
                                                        <option value="">--- Seleccione Opción ---</option>
                                                        @foreach ($resoluciontipos as $resoluciontipo)
                                                            <option value="{{$resoluciontipo->id}}">{{$resoluciontipo->id.': '.$resoluciontipo->x_resoluciontipos}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-error" v-if="errors.resoluciontipo">@{{ errors.resoluciontipo[0] }}</div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="dependencia">DEPENDENCIA-UGEL <span class="obligatorio">*</span></label>
                                                    <select id="dependencia" v-model="resolucion.dependencia" class="form-control" :class="[errors.dependencia ? 'border-error' : '']">
                                                        <option value="">--- Seleccione Opción ---</option>
                                                        @foreach ($dependencias as $dependencia)
                                                            <option value="{{$dependencia->id}}">{{$dependencia->id.': '.$dependencia->x_nombre}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-error" v-if="errors.dependencia">@{{ errors.dependencia[0] }}</div>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label for="visto">VISTO<span class="obligatorio">*</span></label> 
                                                    <textarea  id="visto" rows="3" v-model="resolucion.visto" class="form-control" :class="[errors.visto ? 'border-error' : '']" maxlength="300"></textarea>
                                                    <div class="input-error" v-if="errors.visto">@{{ errors.visto[0] }}</div>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="asunto">ASUNTO<span class="obligatorio">*</span></label> 
                                                    <textarea  id="asunto" rows="3" v-model="resolucion.asunto" class="form-control" :class="[errors.asunto ? 'border-error' : '']" maxlength="250"></textarea>
                                                    <div class="input-error" v-if="errors.asunto">@{{ errors.asunto[0] }}</div>
                                                </div>
                                            
                                                <!--<div class="form-group col-md-12">
                                                    <label for="archivo">SUBIR ARCHIVO - * PDF<span class="obligatorio">*</span></label> 
                                                    <input class="form-control" type="file" id="archivo" @change="Archivo"  :class="[errors.archivo ? 'border-error' : '']" >
                                                    <div class="input-error" v-if="errors.archivo">@{{ errors.archivo[0] }}</div>     
                                                </div>-->

                                                    <div class="col-md-12 mt-3 mb-4">
                                                        <button class="btn btn-block btn-warning" @click="Update('edit')"><i class="fa fa-check-square"></i> Actualizar registro</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- EDIT --}}

                                {{-- ENVIAR --}}
                                <div class="modal-content" v-if="modal.method == 'loked'" id="loked" style="background: rgb(243, 243, 243)">
                                    <div class="modal-body" style="padding: 0.8rem 1.3rem" >
                                        <button type="button" class="btn-close" data-dismiss="modal" @click="CloseModal"><i class="fas fa-times"></i></button>
                                        <div class="text-center mb-4 mt-4">
                                            <h3>ENVIAR EMAIL</h3>
                                        </div>
                                        <div class="card card-dark card-outline" style="background: rgb(243, 243, 243)">
                                            <div class="m-2">
                                                <div class="form-row">
                                                    <div class="col-md-12 obligatorio text-right" style="max-height: 15px;">
                                                        *<small style="vertical-align: top;"> Obligatorio</small>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                            <label for="persona">RESPONSABLE <span class="obligatorio">*</span></label>
                                                            <div class="select-dropdown">
                                                                <button type="button" class="btn-dropdown form-control form-control text-left" data-toggle="dropdown" @click="Autofocus('persona_search')">@{{resolucion.persona_text}} <span><i class="fas fa-angle-down"></i></span></button>
                                                                <div class="dropdown-menu dropdown-sm lista-li-select2" id="myDropdownp" style="bottom: 0 !important; height: 300px; min-width: 98%;">
                                                                    <div class="input-search">
                                                                        <span><i class="fas fa-search"></i></span>
                                                                        <input type="text" id="persona_search" class="form-control form-control-sm rounded-0" onkeyup="myFunctionp()" autocomplete="off" >
                                                                    </div>
                                                                    <a class="dropdown-item" v-for="persona in personas" @click="SelecPersona(persona)" :class="[(resolucion.persona_id == persona.id) ? 'active' : '']">@{{persona.c_dni+': '+persona.x_nombre+'- '+persona.x_email}}</a>
                                                                </div>
                                                            </div>
                                                            <div class="input-error" v-if="errors.persona">@{{ errors.persona[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="numero">RESOLUCION N°<span class="obligatorio">*</span></label>
                                                        <input type="text"  disabled="false" id="numero" v-model="resolucion.numero" class="form-control" :class="[errors.numero ? 'border-error' : '']" maxlength="15" onkeypress="Numero()">
                                                        <div class="input-error" v-if="errors.numero">@{{ errors.numero[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="fecha" >FECHA DE ENVIO <span class="obligatorio">*</span></label>
                                                        <input type="date"  disabled="false" id="fecha"  v-model="resolucion.fecha" class="form-control" :class="[errors.fecha ? 'border-error' : '']">
                                                        <div class="input-error" v-if="errors.fecha">@{{ errors.fecha[0] }}</div>
                                                        <script>
                                                            var date = new Date();
                                                            var year = date.getFullYear();
                                                            var month = String(date.getMonth()+1).padStart(2,'0');
                                                            var todayDate = String(date.getDate()).padStart(2,'0');
                                                            var datePattern = year + '-' + month + '-' + todayDate;
                                                            document.getElementById("fecha").value = datePattern;
                                                        </script>
                                                    </div>
                                                   
                                                    <div class="form-group col-md-12">
                                                        <label for="asunto">ASUNTO<span class="obligatorio">*</span></label> 
                                                        <textarea  id="asunto" rows="3" v-model="resolucion.asunto" class="form-control" :class="[errors.asunto ? 'border-error' : '']" maxlength="150"></textarea>
                                                        <div class="input-error" v-if="errors.asunto">@{{ errors.asunto[0] }}</div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="archivo">SUBIR ARCHIVO - * PDF<span class="obligatorio">*</span></label> 
                                                        <input class="form-control" type="file" id="archivo"  @change="Archivo"  :class="[errors.archivo ? 'border-error' : '']" >
                                                        <div class="input-error" v-if="errors.archivo">@{{ errors.archivo[0] }}</div>     
                                                    </div>

                                                    <div class="col-md-12 mt-3 mb-4">
                                                        <button class="btn btn-block btn-dark" @click="Enviar('loked')"><i class="fa fa-check-square"></i> Enviar registro</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                                {{-- ENVIAR --}}


                                {{-- DELETE --}}
                                <div class="modal-content" v-if="modal.method == 'delete'" id="delete">
                                    <div class="modal-body text-center" style="padding: 0.8rem 1.3rem">
                                        <button type="button" class="btn-close" data-dismiss="modal" @click="CloseModal"><i class="fas fa-times"></i></button>
                                        <div class="text-center mb-4 mt-4">
                                            <h3>ELIMINAR RESOLUCION</h3>
                                        </div>
                                        <div class="card card-danger card-outline">
                                            <div class="m-2">
                                                <p class="text-center">¿ Realmente desea eliminar la Resolución: <br>
                                                    <strong>@{{resolucion.numero}}</strong> ?</p>

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
                                                <a class="dropdown-item" href="#" :class="[(search.filter == 1) ? 'active' : '']" @click="Filtrar('Numero de la Resolucion', 1)">Resolucion</a>
                                                <a class="dropdown-item" href="#" :class="[(search.filter == 2) ? 'active' : '']" @click="Filtrar('Tipo de Resolucion', 2)">Tipo</a>
                                                
                                            </div>
                                        </div>
                                      <template v-if="search.filter == 1">
                                        <input type="text" v-model="search.datos" class="form-control" :placeholder="search.text_filter" @keyup.enter="Buscar">
                                        <div class="input-group-append">
                                            <button type="button" class="btn bg-gradient-success" @click="Buscar"><i class="fas fa-search"></i> Buscar</button>
                                        </div>
                                      </template>
                                      <select v-model="search.datos" class="form-control" :placeholder="search.text_filter" @change="Buscar" v-else>
                                        <option value="">--- TODOS LOS TIPOS ---</option>
                                        @foreach ($resoluciontipos as $resoluciontipo)
                                            <option value="{{$resoluciontipo->id}}">{{$resoluciontipo->x_resoluciontipos}}</option>
                                        @endforeach
                                      </select>
                                      <select v-model="search.datos" class="form-control" :placeholder="search.text_filter" @change="Buscar" v-else>
                                        <option value="">--- UGELES ---</option>
                                        @foreach ($dependencias as $dependencia)
                                            <option value="{{$dependencia->id}}">{{$dependencia->x_nombre}}</option>
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
                                            <!-- <th width="20%"class="text-left">RESPONSABLE</th>-->
                                            <th width="8%">NUMERO</th>
                                            <th width="15%">TIPO RESOLUCION</th>
                                            <th width="12%">ESTADO</th>
                                            <th width="8%">FECHA</th>
                                            <th width="20%">VISTO</th>
                                            <th width="22%">ASUNTO</th>
                                            <th width="5%" class="text-center">ENVIAR</th>
                                            <th width="5%" class="text-center">FILE</th>
                                            <th width="5%" class="text-center">SEDE/UGEL</th>
                                            @if (($opcion->l_editar == 'S') || ($opcion->l_eliminar == 'S')|| ($opcion->l_ver == 'S'))
                                                <th width="5%" class="text-center">OPC</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(data, index) in listRequest" :class="'active-'+data.l_activo">
                                            <td class="text-center">@{{(index + pagination.index + 1)}}</td>
                                            <!-- <td class="text-left">@{{data.get_persona.x_nombre}}</td>-->
                                            <td class="text-left">@{{data.x_numero}}</td>
                                            <td class="text-left">@{{data.get_resoluciontipo.x_resoluciontipos}}</td>
                                            <td class="text-left">@{{data.x_estado}}</td>
                                            <td class="text-left">@{{data.x_fecha}}</td>
                                            <td class="text-left">@{{data.x_visto}}</td>
                                            <td class="text-left">@{{data.x_asunto}}</td>
                                            <td class="text-center"><i style="color:rgb(0, 0, 0)"> @{{data.x_enviado}}</i></td>
                                            <td class="text-center"><a :href="'../storage/ArchivosPDF/'+ data.x_archivo"  target="_blank"> <button type="button" class="btn btn-default pt-0 pb-0" style="background: rgb(133, 0, 185); border-color:rgb(94, 92, 92); color:rgb(255, 255, 255)" style="width: 100px;"><i class="far fa-file-pdf"></i></button></a></td>                                                                                        
                                            <td class="text-left">@{{data.get_dependencia.x_nombre}}</td>
                                            <td class="text-center">
                                                @if (($opcion->l_editar == 'S') || ($opcion->l_eliminar == 'S')|| ($opcion->l_ver == 'S'))
                                                    <div class="">
                                                        <button type="button"  class="btn bg-gradient-success pt-0 pb-0" style="background: #04AA6D; border-color:#04AA6D; color:rgb(255, 255, 255)" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                                        <div class="dropdown-menu">
                                                            @if ($opcion->l_editar == 'S')
                                                                <a href="#" class="dropdown-item text-warning" data-toggle="modal" data-target="#formularioModal"
                                                                v-on:click="Modal('modal-lg', 'edit', data.id, data)"><i class="fas fa-pen-square"></i> Editar</a>
                                                           
                                                                @endif

                                                            @if ($opcion->l_eliminar == 'S')
                                                                <a href="#" class="dropdown-item text-danger" data-toggle="modal" data-target="#formularioModal"
                                                                v-on:click="Modal('modal-md', 'delete', data.id, data)"><i class="fas fa-window-close"></i> Eliminar</a>
                                                            @endif
                                                            
                                                            @if ($opcion->l_otros == 'S')
                                                                <a href="#" class="dropdown-item text-info" data-toggle="modal" data-target="#formularioModal"
                                                                v-on:click="Modal('modal-md', 'loked', data.id, data)"><i class="far fa-envelope-open"></i> Enviar Ugel</a>
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
    var my_resoluciontipos = {!! json_encode($resoluciontipos) !!};
    var my_personas = {!! json_encode($personas) !!};
    var my_dependencias = {!! json_encode($dependencias) !!};
</script>
    <script src="{{asset('views/tablas/resoluciones.js')}}"></script>
@endsection
