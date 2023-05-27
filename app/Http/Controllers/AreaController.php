<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Submenu;
use App\Models\RolSubmenu;
use App\Models\Direccion;
use App\Models\Area;

class AreaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $submenu = Submenu::where('x_route', Route::currentRouteName())->pluck('id')->first();
        
        if ($opcion = RolSubmenu::where('rol_id', Auth::user()->rol_id)->where('submenu_id', $submenu)->where('l_activo', 'S')->first()) {
           
            $direcciones = Direccion::where('l_activo', 'S')->orderBy('id')->get();

            return view('sistema.tablas.areas', compact('opcion', 'direcciones'));
        } else {
            return redirect('home');
        }
    }


    public function buscar(Request $request)
    {
        $areas = Area::where('l_activo', 'S');

        if ($request->search) {
            $search = $request->search;

            switch ($request->filter)
             {
                case 1:
                    $areas->where(function ($query) use ($search){
                        $query->where('x_areas', 'LIKE', "%{$search}%")
                        ->orWhere('id', 'LIKE', "%{$search}%");
                    });
                    break;
                case 2:
                     $areas->where(function ($query) use ($search){
                        $query->where('direccion_id', $search);
                    });
                    break;
            }
        }

        $areas = $areas->with('getDireccion')->orderBy('id', 'ASC')->paginate(10);
        
        /*$marcas = $marcas->with(['getBien', 'getModelos'])->withCount('getModelos')
        ->orderBy('x_nombre', 'ASC')
        ->paginate(10);*/

        return [
            'pagination' => [
                'total' => $areas->total(),
                'current_page' => $areas->currentPage(),
                'per_page' => $areas->perPage(),
                'last_page' => $areas->lastPage(),
                'from' => $areas->firstItem(),
                'to' => $areas->lastPage(),
                'index' => ($areas->currentPage()-1)*$areas->perPage(),
            ],
            'areas' => $areas,
        ];
    }

   
    public function store(Request $request)
    {
        $this->validate($request, [
           
            'direccion'   => 'required',
            'nombre'   => 'required|max:150',
            //'codigo'   => 'required|digits:8|unique:inv_bienes,c_codigo',
        ]);

        try {

            DB::beginTransaction();

            $area = new Area();
            $area->direccion_id = $request->direccion;
            $area->x_areas = Str::upper($request->nombre);
            $area->user_id = Auth::user()->id;
            $area->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Area se registró con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al crear el Area, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    
    public function update(Request $request)
    {
        $this->validate($request, [
            'direccion'   => 'required',
            'nombre'   => 'required|max:150',
            //'codigo'   => 'required|digits:8|unique:inv_bienes,c_codigo,'.$request->id,
        ]);

        try {

            DB::beginTransaction();

            $area = Area::findOrFail($request->id);
            $area->direccion_id = $request->direccion;
            $area->x_areas = Str::upper($request->nombre);
            $area->user_id = Auth::user()->id;
            $area->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Area se actualizó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al actualizar el Area, intente nuevamente o contacte al Administrador del Sistema. Código de error '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        } 
    }

   
    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $area = Area::findOrFail($request->id);
            $area->l_activo = 'N';
            $area->user_id = Auth::user()->id;
            $area->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Area se eliminó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al eliminar el Area, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }
    
}
