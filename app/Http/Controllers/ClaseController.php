<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Submenu;
use App\Models\RolSubmenu;
use App\Models\Clase;

class ClaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $submenu = Submenu::where('x_route', Route::currentRouteName())->pluck('id')->first();
        
        if ($opcion = RolSubmenu::where('rol_id', Auth::user()->rol_id)->where('submenu_id', $submenu)->where('l_activo', 'S')->first()) {
            return view('sistema.tablas.clases', compact('opcion'));
        } else {
            return redirect('home');
        }
    }

    public function buscar(Request $request)
    {
        $clases = Clase::where('l_activo', 'S');

        if ($request->search) {
            $search = $request->search;
            
            switch ($request->filter) {
                case 1:
                    $clases->where(function ($query) use ($search){
                        $query->where('x_nombre', 'LIKE', "%{$search}%")
                        ->orWhere('c_codigo', 'LIKE', "%{$search}%");
                    });
                    break;
            }
        }

        $clases = $clases->orderBy('c_codigo', 'ASC')->paginate(10);

        return [
            'pagination' => [
                'total' => $clases->total(),
                'current_page' => $clases->currentPage(),
                'per_page' => $clases->perPage(),
                'last_page' => $clases->lastPage(),
                'from' => $clases->firstItem(),
                'to' => $clases->lastPage(),
                'index' => ($clases->currentPage()-1)*$clases->perPage(),
            ],
            'clases' => $clases,
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre'   => 'required|max:100',
            'codigo'   => 'required|digits:2',
        ]);

        try {

            DB::beginTransaction();

            $clase = new Clase();
            $clase->x_nombre = Str::upper($request->nombre);
            $clase->c_codigo = $request->codigo;
            $clase->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Clase se registró con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al crear la Clase, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'nombre'   => 'required|max:100',
            'codigo'   => 'required|digits:2',
        ]);

        try {

            DB::beginTransaction();

            $clase = Clase::findOrFail($request->id);
            $clase->x_nombre = Str::upper($request->nombre);
            $clase->c_codigo = $request->codigo;
            $clase->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Clase se actualizó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al actualizar la Clase, intente nuevamente o contacte al Administrador del Sistema. Código de error '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $clase = Clase::findOrFail($request->id);
            $clase->l_activo = 'N';
            $clase->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Clase se eliminó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al eliminar la Clase, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }
}
