<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Submenu;
use App\Models\RolSubmenu;
use App\Models\Unidad;

class UnidadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $submenu = Submenu::where('x_route', Route::currentRouteName())->pluck('id')->first();
        
        if ($opcion = RolSubmenu::where('rol_id', Auth::user()->rol_id)->where('submenu_id', $submenu)->where('l_activo', 'S')->first()) {
            return view('sistema.tablas.unidades', compact('opcion'));
        } else {
            return redirect('home');
        }
    }

    public function buscar(Request $request)
    {
        $unidades = Unidad::where('l_activo', 'S');

        if ($request->search) {
            $search = $request->search;
            
            switch ($request->filter) {
                case 1:
                    $unidades->where(function ($query) use ($search){
                        $query->where('x_unidades', 'LIKE', "%{$search}%");
                    });
                    break;
            }
        }

        $unidades = $unidades->orderBy('id', 'ASC')->paginate(10);

        return [
            'pagination' => [
                'total' => $unidades->total(),
                'current_page' => $unidades->currentPage(),
                'per_page' => $unidades->perPage(),
                'last_page' => $unidades->lastPage(),
                'from' => $unidades->firstItem(),
                'to' => $unidades->lastPage(),
                'index' => ($unidades->currentPage()-1)*$unidades->perPage(),
            ],
            'unidades' => $unidades,
        ];
    }
   
   
    public function store(Request $request)
    {
        $this->validate($request, [
            
            'nombre'   => 'required|max:150',
        ]);

        try {

            DB::beginTransaction();

            $unidad = new Unidad();
            $unidad->x_unidades = Str::upper($request->nombre);
            $unidad->user_id = Auth::user()->id;
            $unidad->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Unidad se registró con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al crear la Unidad, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            
            'nombre'   => 'required|max:150',
        ]);

        try {

            DB::beginTransaction();

            $unidad = Unidad::findOrFail($request->id);
            $unidad->x_unidades = Str::upper($request->nombre);
            $unidad->user_id = Auth::user()->id;
            $unidad->save();
            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Unidad se actualizó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al actualizar la Unidad, intente nuevamente o contacte al Administrador del Sistema. Código de error '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

   
    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $unidad = Unidad::findOrFail($request->id);
            $unidad->l_activo = 'N';
            $unidad->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Unidad se eliminó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al eliminar la Unidad, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }
}
