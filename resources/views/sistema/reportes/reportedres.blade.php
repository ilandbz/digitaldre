@extends('layouts.app')

@section('css')

<link href=https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css>
<link href=https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css>
<link rel="stylesheet" href="/css/admin_custom.css">
<link href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
                        <h1><i class="fas fa-file"></i> REPORTE</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                            <li class="breadcrumb-item active">COLABORADORES-DRE</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
       
    <section class="content">
        
        <div class="container-fluid">
            <!--<button type="button" class="btn btn-danger" style="width: 110px;" value="Imprimir" onClick="imprimir()"> <i class="fas fa-print"></i> Imprimir</button>-->
           <div class="row" style="padding-top: 10px;" id="ESTILO">
            <div class="col-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title" style="color: #00b1b1;">REPORTE DE COLABORADORES-DRE</h3>  
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
                        <div class="table-responsive" id="my_table" style="min-height: 140px;">
                            
                                <table id="reportedres" class="table table-md table-hover" style="width:100%">
                                    <thead class="bg-primary text-white">
                                       
                                            <tr style="background-color: #747474;">
                                                <th width="5%" class="text-center">#</th>
                                                <th width="10%"class="text-left">COLABORADOR</th>
                                                <th width="8%" class="text-center">DNI</th>
                                                <th width="10%">DIRECCION</th>
                                                <th width="15%">AREA</th>
                                                <th width="10%">UNIDAD</th>
                                                <th width="10%">CARGO</th>
                                                <th width="8%">TIPO</th>
                                                <th width="8%">TEL-P</th>
                                                <th width="8%">TEL-T</th>
                                                <th width="10%">EMAIL P</th>
                                                <th width="10%">EMAIL C</th>
                                                <th width="15%">UGEL</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reportedres as $reportedre)
                                        <tr>
                                            <td>{{$reportedre->id}}</td>
                                            <td>{{$reportedre->x_persona}}</td>
                                            <td>{{$reportedre->c_dni}}</td>
                                            <td>{{$reportedre->x_direcciones}}</td>
                                            <td>{{$reportedre->x_areas}}</td>
                                            <td>{{$reportedre->x_unidades}}</td>
                                            <td>{{$reportedre->x_cargos}}</td>
                                            <td>{{$reportedre->x_tipopersonal}}</td>
                                            <td>{{$reportedre->x_telefono}}</td>
                                            <td>{{$reportedre->x_telefono2}}</td>
                                            <td>{{$reportedre->x_email}}</td>
                                            <td>{{$reportedre->x_email2}}</td>
                                            <td>{{$reportedre->x_nombre}}</td>
                                        </tr>
                                        @endforeach
                                        
                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
           </div>
        </div>
    </section>
@stop
@section('css')
   
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap5.min.js"></script>

<!--<script src=https://code.jquery.com/jquery-3.5.1.js></script>-->
<!--<script src=https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js></script>-->
<script src=https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js></script>
<script src=https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js></script>
<script src=https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js></script>
<script src=https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js></script>
<script src=https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js></script>
<script src=https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js></script>
<script>
$(document).ready(function() 
   {
    $('#reportedres').DataTable(
        {
            language: {
                "sSearch": "BUSCAR:",
                "info": "MOSTRANDO _START_ al _END_ de _TOTAL_ REGISTROS",
                "infoFiltered": "(Filtrado un total de _MAX_ registros)",
            },
           /* language: {
                "lengthMenu": "Mostrar _MENU_ registros",
                "zeroRecords": "No se encontraron resultados",
                "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "infoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sSearch": "Buscar:",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast":"Último",
                    "sNext":"Siguiente",
                    "sPrevious": "Anterior"
			     },
			     "sProcessing":"Procesando...",
            },*/
        //"lengthMenu": [[5,10, 50, -1], [5, 10, 50, "All"]]
        responsive: "true",
        dom: 'Bfrtip',
        buttons: [
            {
				extend:    'print',
				text:      '<i class="fas fa-print"> Imprimir</i> ',
				titleAttr: 'Imprimir',
				className: 'btn btn-warning'
			},
            {
				extend:    'excelHtml5',
				text:      '<i class="fas fa-file-excel"> Excel</i> ',
				titleAttr: 'Exportar a Excel',
				className: 'btn btn-success'
			},
            {
				extend:    'pdfHtml5',
				text:      '<i class="fas fa-file-pdf"> PDF</i> ',
				titleAttr: 'Exportar a PDF',
				className: 'btn btn-danger'
			},
            {
				extend:    'csv',
				text:      '<i class="fas fa-file-csv"> CSV</i> ',
				titleAttr: 'Exportar a CSV',
				className: 'btn btn-info'
			},
            {
				extend:    'copy',
				text:      '<i class="fas fa-copy"> Copiar</i> ',
				titleAttr: 'Copiar Tabla',
				className: 'btn btn-secondary'
			},
        ]
        });
   } );
       
function imprimir()
        {
       
        window.print();
       
        }
</script>

@stop