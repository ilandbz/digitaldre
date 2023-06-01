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
                    <h1><i class="fas fa-user-tie"></i>  PERSONA-RESOLUCION</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item active"> PERSONA-RESOLUCION</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row" style="padding-top: 10px;" id="resolucionpersonas">
                <div class="col-12">
                    <div class="card card-dark card-outline">
                        <div class="card-header">
                            <h3 class="card-title">REGISTRO GENERAL DE RESOLUCIONES</h3>
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

                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-lg-8">
                                    <button type="button" class="btn btn-warning" style="width: 110px;" value="Imprimir" onClick="imprimir()"> <i class="fas fa-print"></i> Imprimir</button>
                                   <!-- <button type="button" class="btn btn-warning" style="width: 110px;" value="Imprimir" onClick="imprimir()"> <i class="fas fa-file-excel" ></i> Exportar</button>-->
                                </div>
                               
                                <div class="col-lg-4">

                                </div>
                            </div>

                            <div class="table-responsive" id="my_table" style="min-height: 140px;">
                                <table class="table table-lg table-hover">
                                    <thead>
                                        <tr style="background-color: #f2f2f2;">
                                            <th width="5%" class="text-center">#</th>
                                            <th width="5%" class="text-left">DNI</th>
                                            <th width="25%" class="text-left">COLABORADOR</th>
                                            <th width="10%" class="text-left">RESOLUCION</th>
                                            <th width="10%" class="text-left">FECHA</th>
                                            <th width="20%" class="text-left">ASUNTO</th>
                                            <th width="10%" class="text-left">ENVIADO</th>
                                            <th width="10%" class="text-left">PDF</th>
                                            <th width="5%" class="text-center">OPCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(data, index) in listRequest" :class="'active-'+data.l_activo">
                                            <td class="text-center">@{{(index + pagination.index + 1)}}</td>
                                            <td class="text-left">@{{data.get_persona.c_dni}}</td>  
                                            <td class="text-left">@{{data.get_persona.x_nombre}}</td>        
                                            <td class="text-left"> @{{data.get_resolucion.x_numero}}</td>
                                            <td class="text-left"> @{{data.get_resolucion.x_fecha}}</td>
                                            <td class="text-left"> @{{data.get_resolucion.x_asunto}}</td>
                                            <td class="text-left"> @{{data.x_enviado}}</td>
                                            <td class="text-center"><a :href="'../storage/ArchivosPDF/'+ data.get_resolucion.x_archivo"  target="_blank"> <button type="button" class="btn btn-default pt-0 pb-0" style="background: rgb(133, 0, 185); border-color:rgb(94, 92, 92); color:rgb(255, 255, 255)" style="width: 100px;"><i class="far fa-file-pdf"></i></button></a></td>
                                            <td class="text-center">

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
    var my_resoluciones={!!json_encode($resoluciones)!!};
    var my_personas={!!json_encode($personas)!!};
    var estado='publico';
</script>
    <script src="{{asset('views/tablas/resolucionpersonas.js')}}"></script>
@endsection
