<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => ['XssSanitizer']], function () {

    Route::get('/', function () {
        //$user = DB::connection('sybase')->table('historia')->get();
        return redirect('login');
    });

    Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
    //Auth::routes();

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/home/perfil', [App\Http\Controllers\HomeController::class, 'perfil']);

    Route::get('/buscar', [App\Http\Controllers\GrupoController::class, 'index'])->name('buscar');

    //------------------- INVENTARIO -----------------------------
    Route::get('/tablas/grupos', [App\Http\Controllers\GrupoController::class, 'index'])->name('tablas.grupos');
    Route::post('/tablas/grupos/buscar', [App\Http\Controllers\GrupoController::class, 'buscar']);
    Route::post('/tablas/grupos/store', [App\Http\Controllers\GrupoController::class, 'store']);
    Route::post('/tablas/grupos/update', [App\Http\Controllers\GrupoController::class, 'update']);
    Route::post('/tablas/grupos/delete', [App\Http\Controllers\GrupoController::class, 'delete']);

    Route::get('/tablas/clases', [App\Http\Controllers\ClaseController::class, 'index'])->name('tablas.clases');
    Route::post('/tablas/clases/buscar', [App\Http\Controllers\ClaseController::class, 'buscar']);
    Route::post('/tablas/clases/store', [App\Http\Controllers\ClaseController::class, 'store']);
    Route::post('/tablas/clases/update', [App\Http\Controllers\ClaseController::class, 'update']);
    Route::post('/tablas/clases/delete', [App\Http\Controllers\ClaseController::class, 'delete']);

    Route::get('/tablas/bienes', [App\Http\Controllers\BienController::class, 'index'])->name('tablas.bienes');
    Route::post('/tablas/bienes/buscar', [App\Http\Controllers\BienController::class, 'buscar']);
    Route::post('/tablas/bienes/store', [App\Http\Controllers\BienController::class, 'store']);
    Route::post('/tablas/bienes/update', [App\Http\Controllers\BienController::class, 'update']);
    Route::post('/tablas/bienes/delete', [App\Http\Controllers\BienController::class, 'delete']);

    Route::get('/tablas/marcas', [App\Http\Controllers\MarcaController::class, 'index'])->name('tablas.marcas');
    Route::post('/tablas/marcas/buscar', [App\Http\Controllers\MarcaController::class, 'buscar']);
    Route::post('/tablas/marcas/store', [App\Http\Controllers\MarcaController::class, 'store']);
    Route::post('/tablas/marcas/update', [App\Http\Controllers\MarcaController::class, 'update']);
    Route::post('/tablas/marcas/delete', [App\Http\Controllers\MarcaController::class, 'delete']);
    Route::post('/tablas/marcas/modelos', [App\Http\Controllers\MarcaController::class, 'modelos']);

    Route::get('/tablas/modelos', [App\Http\Controllers\ModeloController::class, 'index'])->name('tablas.modelos');
    Route::post('/tablas/modelos/buscar', [App\Http\Controllers\ModeloController::class, 'buscar']);
    Route::post('/tablas/modelos/store', [App\Http\Controllers\ModeloController::class, 'store']);
    Route::post('/tablas/modelos/update', [App\Http\Controllers\ModeloController::class, 'update']);
    Route::post('/tablas/modelos/delete', [App\Http\Controllers\ModeloController::class, 'delete']);

    Route::get('/tablas/cargos', [App\Http\Controllers\CargoController::class, 'index'])->name('tablas.cargos');
    Route::post('/tablas/cargos/buscar', [App\Http\Controllers\CargoController::class, 'buscar']);
    Route::post('/tablas/cargos/store', [App\Http\Controllers\CargoController::class, 'store']);
    Route::post('/tablas/cargos/update', [App\Http\Controllers\CargoController::class, 'update']);
    Route::post('/tablas/cargos/delete', [App\Http\Controllers\CargoController::class, 'delete']);
   
    Route::get('/tablas/direcciones', [App\Http\Controllers\DireccionController::class, 'index'])->name('tablas.direcciones');
    Route::post('/tablas/direcciones/buscar', [App\Http\Controllers\DireccionController::class, 'buscar']);
    Route::post('/tablas/direcciones/store', [App\Http\Controllers\DireccionController::class, 'store']);
    Route::post('/tablas/direcciones/update', [App\Http\Controllers\DireccionController::class, 'update']);
    Route::post('/tablas/direcciones/delete', [App\Http\Controllers\DireccionController::class, 'delete']);
   
    Route::get('/tablas/direccionues', [App\Http\Controllers\DireccionuController::class, 'index'])->name('tablas.direccionues');
    Route::post('/tablas/direccionues/buscar', [App\Http\Controllers\DireccionuController::class, 'buscar']);
    Route::post('/tablas/direccionues/store', [App\Http\Controllers\DireccionuController::class, 'store']);
    Route::post('/tablas/direccionues/update', [App\Http\Controllers\DireccionuController::class, 'update']);
    Route::post('/tablas/direccionues/delete', [App\Http\Controllers\DireccionuController::class, 'delete']);


    Route::get('/tablas/areas', [App\Http\Controllers\AreaController::class, 'index'])->name('tablas.areas');
    Route::post('/tablas/areas/buscar', [App\Http\Controllers\AreaController::class, 'buscar']);
    Route::post('/tablas/areas/store', [App\Http\Controllers\AreaController::class, 'store']);
    Route::post('/tablas/areas/update', [App\Http\Controllers\AreaController::class, 'update']);
    Route::post('/tablas/areas/delete', [App\Http\Controllers\AreaController::class, 'delete']);

    Route::get('/tablas/areasu', [App\Http\Controllers\AreauController::class, 'index'])->name('tablas.areasu');
    Route::post('/tablas/areasu/buscar', [App\Http\Controllers\AreauController::class, 'buscar']);
    Route::post('/tablas/areasu/store', [App\Http\Controllers\AreauController::class, 'store']);
    Route::post('/tablas/areasu/update', [App\Http\Controllers\AreauController::class, 'update']);
    Route::post('/tablas/areasu/delete', [App\Http\Controllers\AreauController::class, 'delete']);

    Route::get('/tablas/unidades', [App\Http\Controllers\UnidadController::class, 'index'])->name('tablas.unidades');
    Route::post('/tablas/unidades/buscar', [App\Http\Controllers\UnidadController::class, 'buscar']);
    Route::post('/tablas/unidades/store', [App\Http\Controllers\UnidadController::class, 'store']);
    Route::post('/tablas/unidades/update', [App\Http\Controllers\UnidadController::class, 'update']);
    Route::post('/tablas/unidades/delete', [App\Http\Controllers\UnidadController::class, 'delete']);
  
    Route::get('/tablas/ugeles', [App\Http\Controllers\UgelController::class, 'index'])->name('tablas.ugeles');
    Route::post('/tablas/ugeles/buscarareau', [App\Http\Controllers\UgelController::class, 'buscarareau']); 
    Route::post('/tablas/ugeles/buscar', [App\Http\Controllers\UgelController::class, 'buscar']);

    Route::post('/tablas/ugeles/store', [App\Http\Controllers\UgelController::class, 'store']);
    Route::post('/tablas/ugeles/update', [App\Http\Controllers\UgelController::class, 'update']);
    Route::post('/tablas/ugeles/delete', [App\Http\Controllers\UgelController::class, 'delete']);
    Route::post('/tablas/ugeles/activar', [App\Http\Controllers\UgelController::class, 'activar']);

    Route::get('/reportes/colegios', [App\Http\Controllers\ColegioController::class, 'index'])->name('reportes.colegios');
    Route::post('/reportes/colegios/buscar', [App\Http\Controllers\ColegioController::class, 'buscar']);
   
    Route::get('/reportes/reportedres', [App\Http\Controllers\ReportedreController::class, 'index'])->name('reportes.reportedres');
   // Route::post('/reportes/colegios/buscar', [App\Http\Controllers\ColegioController::class, 'buscar']);
  
   //------------------- resoluciones -----------------------------
    Route::get('/tablas/resoluciontipos', [App\Http\Controllers\ResoluciontipoController::class, 'index'])->name('tablas.resoluciontipos');
    Route::post('/tablas/resoluciontipos/buscar', [App\Http\Controllers\ResoluciontipoController::class, 'buscar']);
    Route::post('/tablas/resoluciontipos/store', [App\Http\Controllers\ResoluciontipoController::class, 'store']);
    Route::post('/tablas/resoluciontipos/update', [App\Http\Controllers\ResoluciontipoController::class, 'update']);
    Route::post('/tablas/resoluciontipos/delete', [App\Http\Controllers\ResoluciontipoController::class, 'delete']);
    
    Route::get('/tablas/resoluciones', [App\Http\Controllers\ResolucionController::class, 'index'])->name('tablas.resoluciones');
    Route::post('/tablas/resoluciones/buscar', [App\Http\Controllers\ResolucionController::class, 'buscar']);
    Route::post('/tablas/resoluciones/store', [App\Http\Controllers\ResolucionController::class, 'store']);
    Route::post('/tablas/resoluciones/update', [App\Http\Controllers\ResolucionController::class, 'update']);
    Route::post('/tablas/resoluciones/enviar', [App\Http\Controllers\ResolucionController::class, 'enviar']);//enviar emails
    Route::post('/tablas/resoluciones/delete', [App\Http\Controllers\ResolucionController::class, 'delete']);

    Route::get('/tablas/resolucionpersonas', [App\Http\Controllers\ResolucionpersonaController::class, 'index'])->name('tablas.resolucionpersonas');
    Route::post('/tablas/resolucionpersonas/buscar', [App\Http\Controllers\ResolucionpersonaController::class, 'buscar']);
    Route::post('/tablas/resolucionpersonas/store', [App\Http\Controllers\ResolucionpersonaController::class, 'store']);
    Route::post('/tablas/resolucionpersonas/update', [App\Http\Controllers\ResolucionpersonaController::class, 'update']);
    Route::post('/tablas/resolucionpersonas/delete', [App\Http\Controllers\ResolucionpersonaController::class, 'delete']);

    Route::get('/tablas/formulario-contacto', [App\Http\Controllers\ContactoController::class, 'index'])->name('tablas.formulario-contacto');
    Route::post('/tablas/formulario-contacto/store', [App\Http\Controllers\ContactoController::class, 'store']);
   
    //------------------- ADMINISTRADOR -----------------------------
    Route::get('/sedes', [App\Http\Controllers\SedeController::class, 'index'])->name('sedes');
    Route::post('/sedes/buscar', [App\Http\Controllers\SedeController::class, 'buscar']);
    Route::post('/sedes/store', [App\Http\Controllers\SedeController::class, 'store']);
    Route::post('/sedes/update', [App\Http\Controllers\SedeController::class, 'update']);
    Route::post('/sedes/delete', [App\Http\Controllers\SedeController::class, 'delete']);
    Route::post('/sedes/dependencias', [App\Http\Controllers\SedeController::class, 'dependencias']);

    Route::get('/dependencias', [App\Http\Controllers\DependenciaController::class, 'index'])->name('dependencias');
    Route::post('/dependencias/buscar', [App\Http\Controllers\DependenciaController::class, 'buscar']);
    Route::post('/dependencias/store', [App\Http\Controllers\DependenciaController::class, 'store']);
    Route::post('/dependencias/update', [App\Http\Controllers\DependenciaController::class, 'update']);
    Route::post('/dependencias/delete', [App\Http\Controllers\DependenciaController::class, 'delete']);

    Route::get('/personas', [App\Http\Controllers\PersonaController::class, 'index'])->name('personas');
    Route::post('/personas/buscar', [App\Http\Controllers\PersonaController::class, 'buscar']);
    Route::post('/personas/store', [App\Http\Controllers\PersonaController::class, 'store']);
    Route::post('/personas/update', [App\Http\Controllers\PersonaController::class, 'update']);
    Route::post('/personas/delete', [App\Http\Controllers\PersonaController::class, 'delete']);

    Route::get('/roles', [App\Http\Controllers\RolController::class, 'index'])->name('roles');
    Route::post('/roles/buscar', [App\Http\Controllers\RolController::class, 'buscar']);
    Route::post('/roles/store', [App\Http\Controllers\RolController::class, 'store']);
    Route::post('/roles/update', [App\Http\Controllers\RolController::class, 'update']);
    Route::post('/roles/delete', [App\Http\Controllers\RolController::class, 'delete']);
    Route::get('/roles/submenus/{id}', [App\Http\Controllers\RolController::class, 'submenus']);

    Route::get('/usuarios', [App\Http\Controllers\UsuarioController::class, 'index'])->name('usuarios');
    Route::post('/usuarios/buscar', [App\Http\Controllers\UsuarioController::class, 'buscar']);
    Route::post('/usuarios/store', [App\Http\Controllers\UsuarioController::class, 'store']);
    Route::post('/usuarios/update', [App\Http\Controllers\UsuarioController::class, 'update']);
    Route::post('/usuarios/delete', [App\Http\Controllers\UsuarioController::class, 'delete']);
    Route::post('/usuarios/activar', [App\Http\Controllers\UsuarioController::class, 'activar']);

    Route::get('/trabajadores', [App\Http\Controllers\TrabajadorController::class, 'index'])->name('trabajadores');
    Route::post('/trabajadores/buscar', [App\Http\Controllers\TrabajadorController::class, 'buscar']);
    Route::post('/trabajadores/buscararea', [App\Http\Controllers\TrabajadorController::class, 'buscararea']); 
    Route::post('/trabajadores/store', [App\Http\Controllers\TrabajadorController::class, 'store']);
    Route::post('/trabajadores/update', [App\Http\Controllers\TrabajadorController::class, 'update']);
    Route::post('/trabajadores/delete', [App\Http\Controllers\TrabajadorController::class, 'delete']);
    Route::post('/trabajadores/activar', [App\Http\Controllers\TrabajadorController::class, 'activar']);

    Route::get('storage-link',function(){Artisan::call('storage:link');});
  
    Route::get('/config', [App\Http\Controllers\ConfigController::class, 'index'])->name('config');

    Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->name('logs');
   // Route::resource('iglesias','App\Http\Controllers\IglesiaController');
    //------------------- ADMINISTRADOR -----------------------------

});
