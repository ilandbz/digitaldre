<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Submenu;
use App\Models\RolSubmenu;
use App\Models\Direccionu;
use App\Models\Areau;

class AreauController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $submenu = Submenu::where('x_route', Route::currentRouteName())->pluck('id')->first();
        
        if ($opcion = RolSubmenu::where('rol_id', Auth::user()->rol_id)->where('submenu_id', $submenu)->where('l_activo', 'S')->first()) {
           
            $direcciones = Direccionu::where('l_activo', 'S')->orderBy('id')->get();

            return view('sistema.tablas.areasu', compact('opcion', 'direcciones'));
        } else {
            return redirect('home');
        }
    }


    public function buscar(Request $request)
    {
        $areasu = Areau::where('l_activo', 'S');

        if ($request->search) {
            $search = $request->search;

            switch ($request->filter)
             {
                case 1:
                    $areasu->where(function ($query) use ($search){
                        $query->where('x_areas', 'LIKE', "%{$search}%")
                        ->orWhere('id', 'LIKE', "%{$search}%");
                    });
                    break;
                case 2:
                     $areasu->where(function ($query) use ($search){
                        $query->where('direccion_id', $search);
                    });
                    break;
            }
        }

        $areasu = $areasu->with('getDireccionu')->orderBy('id', 'ASC')->paginate(10);
        
        /*$marcas = $marcas->with(['getBien', 'getModelos'])->withCount('getModelos')
        ->orderBy('x_nombre', 'ASC')
        ->paginate(10);*/

        return [
            'pagination' => [
                'total' => $areasu->total(),
                'current_page' => $areasu->currentPage(),
                'per_page' => $areasu->perPage(),
                'last_page' => $areasu->lastPage(),
                'from' => $areasu->firstItem(),
                'to' => $areasu->lastPage(),
                'index' => ($areasu->currentPage()-1)*$areasu->perPage(),
            ],
            'areasu' => $areasu,
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

            $areau = new Areau();
            $areau->direccion_id = $request->direccion;
            $areau->x_areas = Str::upper($request->nombre);
            $areau->user_id = Auth::user()->id;
            $areau->save();

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

            $areau = Areau::findOrFail($request->id);
            $areau->direccion_id = $request->direccion;
            $areau->x_areas = Str::upper($request->nombre);
            $areau->user_id = Auth::user()->id;
            $areau->save();

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

            $areau = Areau::findOrFail($request->id);
            $areau->l_activo = 'N';
            $areau->user_id = Auth::user()->id;
            $areau->save();

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
