<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Submenu;
use App\Models\RolSubmenu;
use App\Models\Sede;
use App\Models\Dependencia;

class SedeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $submenu = Submenu::where('x_route', Route::currentRouteName())->pluck('id')->first();
        
        if ($opcion = RolSubmenu::where('rol_id', Auth::user()->rol_id)->where('submenu_id', $submenu)->where('l_activo', 'S')->first()) {
            return view('sistema.administrador.sedes', compact('opcion'));
        } else {
            return redirect('home');
        }
    }

    public function buscar(Request $request)
    {
        $sedes = Sede::where('l_activo', 'S');

        if ($request->search) {
            $search = $request->search;
            
            switch ($request->filter) {
                case 1:
                    $sedes->where(function ($query) use ($search){
                        $query->where('x_nombre', 'LIKE', "%{$search}%")
                        ->orWhere('c_codigo', 'LIKE', "%{$search}%");
                    });
                    break;
            }
        }

        $sedes = $sedes->withCount('getDependencias')
        ->with('getDependencias')
        ->orderBy('c_codigo', 'ASC')
        ->paginate(10);

        return [
            'pagination' => [
                'total' => $sedes->total(),
                'current_page' => $sedes->currentPage(),
                'per_page' => $sedes->perPage(),
                'last_page' => $sedes->lastPage(),
                'from' => $sedes->firstItem(),
                'to' => $sedes->lastPage(),
                'index' => ($sedes->currentPage()-1)*$sedes->perPage(),
            ],
            'sedes' => $sedes,
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre'   => 'required|max:100',
            'codigo'   => 'required|digits:4',
        ]);

        try {

            DB::beginTransaction();

            $sede = new Sede();
            $sede->x_nombre = Str::upper($request->nombre);
            $sede->c_codigo = $request->codigo;
            $sede->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Sede se registró con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al crear la Sede, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'nombre'   => 'required|max:100',
            'codigo'   => 'required|digits:4',
        ]);

        try {

            DB::beginTransaction();

            $sede = Sede::findOrFail($request->id);
            $sede->x_nombre = Str::upper($request->nombre);
            $sede->c_codigo = $request->codigo;
            $sede->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Sede se actualizó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al actualizar la Sede, intente nuevamente o contacte al Administrador del Sistema. Código de error '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $sede = Sede::findOrFail($request->id);
            $sede->l_activo = 'N';
            $sede->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Sede se eliminó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al eliminar la Sede, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function dependencias(Request $request)
    {
        $dependencias = Dependencia::where('l_activo', 'S')
        ->where('sede_id', $request->id)
        ->orderBy('c_codigo', 'ASC')
        ->get();

        return $dependencias;
    }
}
