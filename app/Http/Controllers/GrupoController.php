<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Submenu;
use App\Models\RolSubmenu;
use App\Models\Grupo;

class GrupoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $submenu = Submenu::where('x_route', Route::currentRouteName())->pluck('id')->first();
        
        if ($opcion = RolSubmenu::where('rol_id', Auth::user()->rol_id)->where('submenu_id', $submenu)->where('l_activo', 'S')->first()) {
            return view('sistema.tablas.grupos', compact('opcion'));
        } else {
            return redirect('home');
        }
    }

    public function buscar(Request $request)
    {
        $grupos = Grupo::where('l_activo', 'S');

        if ($request->search) {
            $search = $request->search;
            
            switch ($request->filter) {
                case 1:
                    $grupos->where(function ($query) use ($search){
                        $query->where('x_nombre', 'LIKE', "%{$search}%")
                        ->orWhere('c_codigo', 'LIKE', "%{$search}%");
                    });
                    break;
            }
        }

        $grupos = $grupos->orderBy('c_codigo', 'ASC')->paginate(10);

        return [
            'pagination' => [
                'total' => $grupos->total(),
                'current_page' => $grupos->currentPage(),
                'per_page' => $grupos->perPage(),
                'last_page' => $grupos->lastPage(),
                'from' => $grupos->firstItem(),
                'to' => $grupos->lastPage(),
                'index' => ($grupos->currentPage()-1)*$grupos->perPage(),
            ],
            'grupos' => $grupos,
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre'   => 'required|max:100',
            'codigo'   => 'required|digits:2',
        ]);

        try {

            DB::beginTransaction();

            $grupo = new Grupo();
            $grupo->x_nombre = Str::upper($request->nombre);
            $grupo->c_codigo = $request->codigo;
            $grupo->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Grupo se registró con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al crear el Grupo, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'nombre'   => 'required|max:100',
            'codigo'   => 'required|digits:2',
        ]);

        try {

            DB::beginTransaction();

            $grupo = Grupo::findOrFail($request->id);
            $grupo->x_nombre = Str::upper($request->nombre);
            $grupo->c_codigo = $request->codigo;
            $grupo->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Grupo se actualizó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al actualizar el Grupo, intente nuevamente o contacte al Administrador del Sistema. Código de error '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $grupo = Grupo::findOrFail($request->id);
            $grupo->l_activo = 'N';
            $grupo->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Grupo se eliminó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al eliminar el Grupo, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }
}
