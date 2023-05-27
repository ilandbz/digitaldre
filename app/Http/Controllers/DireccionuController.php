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

class DireccionuController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $submenu = Submenu::where('x_route', Route::currentRouteName())->pluck('id')->first();
        
        if ($opcion = RolSubmenu::where('rol_id', Auth::user()->rol_id)->where('submenu_id', $submenu)->where('l_activo', 'S')->first()) {
            return view('sistema.tablas.direccionues', compact('opcion'));
        } else {
            return redirect('home');
        }
    }

   
    public function buscar(Request $request)
    {
        $direccionues = Direccionu::where('l_activo', 'S');

        if ($request->search) {
            $search = $request->search;
            
            switch ($request->filter) {
                case 1:
                    $direccionues->where(function ($query) use ($search){
                        $query->where('x_direcciones', 'LIKE', "%{$search}%");
                    });
                    break;
            }
        }

        $direccionues = $direccionues->orderBy('id', 'ASC')->paginate(10);

        return [
            'pagination' => [
                'total' => $direccionues->total(),
                'current_page' => $direccionues->currentPage(),
                'per_page' => $direccionues->perPage(),
                'last_page' => $direccionues->lastPage(),
                'from' => $direccionues->firstItem(),
                'to' => $direccionues->lastPage(),
                'index' => ($direccionues->currentPage()-1)*$direccionues->perPage(),
            ],
            'direccionues' => $direccionues,
        ];
    }

   
    public function store(Request $request)
    {
        $this->validate($request, [
            
            'nombre'   => 'required|max:20',
        ]);

        try {

            DB::beginTransaction();

            $direccionu = new Direccionu();
            $direccionu->x_direcciones = Str::upper($request->nombre);
            $direccionu->user_id = Auth::user()->id;
            $direccionu->save();

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

            $direccionu = Direccionu::findOrFail($request->id);
            $direccionu->x_direcciones = Str::upper($request->nombre);
            $direccionu->user_id = Auth::user()->id;
            $direccionu->save();
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

            $direccionu = Direccionu::findOrFail($request->id);
            $direccionu->l_activo = 'N';
            $direccionu->save();

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
