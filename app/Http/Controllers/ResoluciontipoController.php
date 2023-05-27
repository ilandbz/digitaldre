<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Submenu;
use App\Models\RolSubmenu;
use App\Models\Resoluciontipo;

class ResoluciontipoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $submenu = Submenu::where('x_route', Route::currentRouteName())->pluck('id')->first();
        
        if ($opcion = RolSubmenu::where('rol_id', Auth::user()->rol_id)->where('submenu_id', $submenu)->where('l_activo', 'S')->first()) {
            return view('sistema.tablas.resoluciontipos', compact('opcion'));
        } else {
            return redirect('home');
        }
    }

    public function buscar(Request $request)
    {
        $resoluciontipos = Resoluciontipo::where('l_activo', 'S');

        if ($request->search) {
            $search = $request->search;
            
            switch ($request->filter) {
                case 1:
                    $resoluciontipos->where(function ($query) use ($search){
                        $query->where('x_resoluciontipos', 'LIKE', "%{$search}%");
                    });
                    break;
            }
        }

        $resoluciontipos = $resoluciontipos->orderBy('id', 'ASC')->paginate(10);

        return [
            'pagination' => [
                'total' => $resoluciontipos->total(),
                'current_page' => $resoluciontipos->currentPage(),
                'per_page' => $resoluciontipos->perPage(),
                'last_page' => $resoluciontipos->lastPage(),
                'from' => $resoluciontipos->firstItem(),
                'to' => $resoluciontipos->lastPage(),
                'index' => ($resoluciontipos->currentPage()-1)*$resoluciontipos->perPage(),
            ],
            'resoluciontipos' => $resoluciontipos,
        ];
    }
   
   
    public function store(Request $request)
    {
        $this->validate($request, [
            
            'nombre'   => 'required|max:100',
        ]);

        try {

            DB::beginTransaction();

            $resoluciontipo = new Resoluciontipo();
            $resoluciontipo->x_resoluciontipos = Str::upper($request->nombre);
            $resoluciontipo->user_id = Auth::user()->id;
            $resoluciontipo->l_activo = 'S';
            $resoluciontipo->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Tipo de Resolución se registró con éxito.',
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

            $resoluciontipo = Resoluciontipo::findOrFail($request->id);
            $resoluciontipo->x_resoluciontipos = Str::upper($request->nombre);
            $resoluciontipo->user_id = Auth::user()->id;
            $resoluciontipo->l_activo = 'S';
            $resoluciontipo->save();
            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Tipo de Resolución se actualizó con éxito.',
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

            $resoluciontipo = Resoluciontipo::findOrFail($request->id);
            $resoluciontipo->l_activo = 'N';
            $resoluciontipo->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Tipo de Resolución se eliminó con éxito.',
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
