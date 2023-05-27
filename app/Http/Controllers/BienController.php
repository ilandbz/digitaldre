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
use App\Models\Clase;
use App\Models\Grupo;

class BienController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $submenu = Submenu::where('x_route', Route::currentRouteName())->pluck('id')->first();

        if ($opcion = RolSubmenu::where('rol_id', Auth::user()->rol_id)->where('submenu_id', $submenu)->where('l_activo', 'S')->first()) {
            $grupos = Grupo::where('l_activo', 'S')->orderBy('c_codigo')->get();
            $clases = Clase::where('l_activo', 'S')->orderBy('c_codigo')->get();
            return view('sistema.tablas.bienes', compact('opcion', 'grupos', 'clases'));
        } else {
            return redirect('home');
        }
    }

    public function buscar(Request $request)
    {
        $bienes = Bien::where('l_activo', 'S');

        if ($request->search) {
            $search = $request->search;

            switch ($request->filter) {
                case 1:
                    $bienes->where(function ($query) use ($search){
                        $query->where('x_nombre', 'LIKE', "%{$search}%")
                        ->orWhere('c_codigo', 'LIKE', "%{$search}%");
                    });
                    break;
            }
        }

        $bienes = $bienes->with('getGrupo', 'getClase')->orderBy('c_codigo', 'ASC')->paginate(10);

        return [
            'pagination' => [
                'total' => $bienes->total(),
                'current_page' => $bienes->currentPage(),
                'per_page' => $bienes->perPage(),
                'last_page' => $bienes->lastPage(),
                'from' => $bienes->firstItem(),
                'to' => $bienes->lastPage(),
                'index' => ($bienes->currentPage()-1)*$bienes->perPage(),
            ],
            'bienes' => $bienes,
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'grupo'   => 'required',
            'clase'   => 'required',
            'nombre'   => 'required|max:100',
            'codigo'   => 'required|digits:8|unique:inv_bienes,c_codigo',
        ]);

        try {

            DB::beginTransaction();

            $bien = new Bien();
            $bien->grupo_id = $request->grupo;
            $bien->clase_id = $request->clase;
            $bien->c_codigo = $request->codigo;
            $bien->x_nombre = Str::upper($request->nombre);
            $bien->x_resolucion = Str::upper($request->resolucion);
            $bien->user_id = Auth::user()->id;
            $bien->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Bien se registró con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al crear el Bien, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'grupo'   => 'required',
            'clase'   => 'required',
            'nombre'   => 'required|max:100',
            'codigo'   => 'required|digits:8|unique:inv_bienes,c_codigo,'.$request->id,
        ]);

        try {

            DB::beginTransaction();

            $bien = Bien::findOrFail($request->id);
            $bien->grupo_id = $request->grupo;
            $bien->clase_id = $request->clase;
            $bien->x_nombre = Str::upper($request->nombre);
            $bien->x_resolucion = Str::upper($request->resolucion);
            $bien->c_codigo = $request->codigo;
            $bien->user_id = Auth::user()->id;
            $bien->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Bien se actualizó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al actualizar el Bien, intente nuevamente o contacte al Administrador del Sistema. Código de error '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $bien = Bien::findOrFail($request->id);
            $bien->l_activo = 'N';
            $bien->user_id = Auth::user()->id;
            $bien->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Bien se eliminó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al eliminar el Bien, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }
}
