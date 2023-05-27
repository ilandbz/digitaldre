<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Submenu;
use App\Models\RolSubmenu;
use App\Models\Dependencia;

class DependenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $submenu = Submenu::where('x_route', Route::currentRouteName())->pluck('id')->first();
        
        if ($opcion = RolSubmenu::where('rol_id', Auth::user()->rol_id)->where('submenu_id', $submenu)->where('l_activo', 'S')->first()) {
            return view('sistema.administrador.dependencias', compact('opcion'));
        } else {
            return redirect('home');
        }
    }

    public function buscar(Request $request)
    {
        $dependencias = Dependencia::where('l_activo', 'S');

        if ($request->search) {
            $search = $request->search;
            
            switch ($request->filter) {
                case 1:
                    $dependencias->where(function ($query) use ($search){
                        $query->where('x_nombre', 'LIKE', "%{$search}%");
                    });
                    break;
            }
        }

        $dependencias = $dependencias->with('getSede')
        ->orderBy('c_codigo', 'ASC')
        ->paginate(10);

        return [
            'pagination' => [
                'total' => $dependencias->total(),
                'current_page' => $dependencias->currentPage(),
                'per_page' => $dependencias->perPage(),
                'last_page' => $dependencias->lastPage(),
                'from' => $dependencias->firstItem(),
                'to' => $dependencias->lastPage(),
                'index' => ($dependencias->currentPage()-1)*$dependencias->perPage(),
            ],
            'sedes' => $dependencias,
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre'   => 'required|max:100',
            'codigo'   => 'required|digits:6',
        ]);

        try {

            DB::beginTransaction();

            $dependencia = new Dependencia();
            $dependencia->sede_id = $request->sede;
            $dependencia->x_nombre = Str::upper($request->nombre);
            $dependencia->c_codigo = $request->codigo;
            $dependencia->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Dependencia se registró con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al crear la Dependencia, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'nombre'   => 'required|max:100',
            'codigo'   => 'required|digits:6',
        ]);

        try {

            DB::beginTransaction();

            $dependencia = Dependencia::findOrFail($request->id);
            $dependencia->x_nombre = Str::upper($request->nombre);
            $dependencia->c_codigo = $request->codigo;
            $dependencia->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Dependencia se actualizó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al actualizar la Dependencia, intente nuevamente o contacte al Administrador del Sistema. Código de error '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $dependencia = Dependencia::findOrFail($request->id);
            $dependencia->l_activo = 'N';
            $dependencia->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Dependencia se eliminó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al eliminar la Dependencia, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }
}
