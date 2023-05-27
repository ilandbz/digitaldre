<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Submenu;
use App\Models\RolSubmenu;
use App\Models\Bien;
use App\Models\Persona;
use App\Models\Modelo;


class ModeloController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $submenu = Submenu::where('x_route', Route::currentRouteName())->pluck('id')->first();
        
        if ($opcion = RolSubmenu::where('rol_id', Auth::user()->rol_id)->where('submenu_id', $submenu)->where('l_activo', 'S')->first()) {
            $bienes = Bien::where('l_activo', 'S')->orderBy('c_codigo')->get();
            $personas = Persona::where('l_activo', 'S')->orderBy('id')->get();
            return view('sistema.tablas.modelos', compact('opcion', 'bienes','personas'));
        } else {
            return redirect('home');
        }
    }

    public function buscar(Request $request)
    {
        $modelos = Modelo::where('l_activo', 'S');

        if ($request->search) {
            $search = $request->search;
            
            switch ($request->filter) {
                case 1:
                    $modelos->where(function ($query) use ($search){
                        $query->where('x_nombre', 'LIKE', "%{$search}%");
                    });
                    break;
            }
        }

        $modelos = $modelos->with('getBien','getPersona')->orderBy('id', 'DESC')->paginate(10);

        return [
            'pagination' => [
                'total' => $modelos->total(),
                'current_page' => $modelos->currentPage(),
                'per_page' => $modelos->perPage(),
                'last_page' => $modelos->lastPage(),
                'from' => $modelos->firstItem(),
                'to' => $modelos->lastPage(),
                'index' => ($modelos->currentPage()-1)*$modelos->perPage(),
            ],
            'modelos' => $modelos,
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            //'bien'   => 'required',
            'nombre'   => 'required|max:100',
        ]);

        try {

            DB::beginTransaction();

            $modelo = new Modelo();
            $modelo->persona_id = $request->persona;
            $modelo->bien_id = $request->bien;
            $modelo->x_nombre = Str::upper($request->nombre);
            $modelo->user_id = Auth::user()->id;
            $modelo->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Modelo se registró con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al crear el Modelo, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            //'bien'   => 'required',
            'nombre'   => 'required|max:100',
        ]);

        try {

            DB::beginTransaction();

            $modelo = Modelo::findOrFail($request->id);
            $modelo->persona_id = $request->persona;
            $modelo->bien_id = $request->bien;
            $modelo->x_nombre = Str::upper($request->nombre);
            $modelo->user_id = Auth::user()->id;
            $modelo->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Modelo se actualizó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al actualizar el Modelo, intente nuevamente o contacte al Administrador del Sistema. Código de error '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $modelo = Modelo::findOrFail($request->id);
            $modelo->l_activo = 'N';
            $modelo->user_id = Auth::user()->id;
            $modelo->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Modelo se eliminó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al eliminar el Modelo, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }
}
