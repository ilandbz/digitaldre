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

class DireccionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $submenu = Submenu::where('x_route', Route::currentRouteName())->pluck('id')->first();
        
        if ($opcion = RolSubmenu::where('rol_id', Auth::user()->rol_id)->where('submenu_id', $submenu)->where('l_activo', 'S')->first()) {
            return view('sistema.tablas.direcciones', compact('opcion'));
        } else {
            return redirect('home');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function buscar(Request $request)
    {
        $direcciones = Direccion::where('l_activo', 'S');

        if ($request->search) {
            $search = $request->search;
            
            switch ($request->filter) {
                case 1:
                    $direcciones->where(function ($query) use ($search){
                        $query->where('x_direcciones', 'LIKE', "%{$search}%");
                    });
                    break;
            }
        }

        $direcciones = $direcciones->orderBy('id', 'ASC')->paginate(10);

        return [
            'pagination' => [
                'total' => $direcciones->total(),
                'current_page' => $direcciones->currentPage(),
                'per_page' => $direcciones->perPage(),
                'last_page' => $direcciones->lastPage(),
                'from' => $direcciones->firstItem(),
                'to' => $direcciones->lastPage(),
                'index' => ($direcciones->currentPage()-1)*$direcciones->perPage(),
            ],
            'direcciones' => $direcciones,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            
            'nombre'   => 'required|max:20',
        ]);

        try {

            DB::beginTransaction();

            $direccion = new Direccion();
            $direccion->x_direcciones = Str::upper($request->nombre);
            $direccion->user_id = Auth::user()->id;
            $direccion->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Dirección se registró con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al crear la Dirección, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            
            'nombre'   => 'required|max:20',
        ]);

        try {

            DB::beginTransaction();

            $direccion = Direccion::findOrFail($request->id);
            $direccion->x_direcciones = Str::upper($request->nombre);
            $direccion->user_id = Auth::user()->id;
            $direccion->save();
            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Dirección se actualizó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al actualizar la Dirección, intente nuevamente o contacte al Administrador del Sistema. Código de error '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

   
    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $direccion = Direccion::findOrFail($request->id);
            $direccion->l_activo = 'N';
            $direccion->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Direccion se eliminó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al eliminar la Direccion, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }
}
