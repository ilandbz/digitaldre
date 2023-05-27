<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Submenu;
use App\Models\RolSubmenu;
use App\Models\Cargo;


class CargoController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $submenu = Submenu::where('x_route', Route::currentRouteName())->pluck('id')->first();
        
        if ($opcion = RolSubmenu::where('rol_id', Auth::user()->rol_id)->where('submenu_id', $submenu)->where('l_activo', 'S')->first()) {
            return view('sistema.tablas.cargos', compact('opcion'));
        } else {
            return redirect('home');
        }
    }

    public function buscar(Request $request)
    {
        $cargos = Cargo::where('l_activo', 'S');

        if ($request->search) {
            $search = $request->search;
            
            switch ($request->filter) {
                case 1:
                    $cargos->where(function ($query) use ($search){
                        $query->where('x_cargos', 'LIKE', "%{$search}%");
                    });
                    break;
            }
        }

        $cargos = $cargos->orderBy('id', 'ASC')->paginate(10);

        return [
            'pagination' => [
                'total' => $cargos->total(),
                'current_page' => $cargos->currentPage(),
                'per_page' => $cargos->perPage(),
                'last_page' => $cargos->lastPage(),
                'from' => $cargos->firstItem(),
                'to' => $cargos->lastPage(),
                'index' => ($cargos->currentPage()-1)*$cargos->perPage(),
            ],
            'cargos' => $cargos,
        ];
    }
   
   
    public function store(Request $request)
    {
        $this->validate($request, [
            
            'nombre'   => 'required|max:100',
        ]);

        try {

            DB::beginTransaction();

            $cargo = new Cargo();
            $cargo->x_cargos = Str::upper($request->nombre);
            $cargo->user_id = Auth::user()->id;
            $cargo->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Zona se registró con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al crear la ZOna, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            
            'nombre'   => 'required|max:100',
        ]);

        try {

            DB::beginTransaction();

            $cargo = Cargo::findOrFail($request->id);
            $cargo->x_cargos = Str::upper($request->nombre);
            $cargo->user_id = Auth::user()->id;
            $cargo->save();
            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Zona se actualizó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al actualizar la zona, intente nuevamente o contacte al Administrador del Sistema. Código de error '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

   
    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $cargo = Cargo::findOrFail($request->id);
            $cargo->l_activo = 'N';
            $cargo->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Zona se eliminó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al eliminar la Zona, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }
}
