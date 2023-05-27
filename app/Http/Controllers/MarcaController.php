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
use App\Models\Marca;
use App\Models\Modelo;

class MarcaController extends Controller
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
            
            return view('sistema.tablas.marcas', compact('opcion', 'bienes'));
        } else {
            return redirect('home');
        }
    }

    public function buscar(Request $request)
    {
        $marcas = Marca::where('l_activo', 'S');

        if ($request->search) {
            $search = $request->search;
            
            switch ($request->filter) {
                case 1:
                    $marcas->where(function ($query) use ($search){
                        $query->where('x_nombre', 'LIKE', "%{$search}%");
                    });
                    break;
                case 2:
                    $marcas->where(function ($query) use ($search){
                        $query->where('bien_id', $search);
                    });
                    break;
            }
        }

        $marcas = $marcas->with(['getBien', 'getModelos'])->withCount('getModelos')->orderBy('x_nombre', 'ASC')->paginate(10);

        return [
            'pagination' => [
                'total' => $marcas->total(),
                'current_page' => $marcas->currentPage(),
                'per_page' => $marcas->perPage(),
                'last_page' => $marcas->lastPage(),
                'from' => $marcas->firstItem(),
                'to' => $marcas->lastPage(),
                'index' => ($marcas->currentPage()-1)*$marcas->perPage(),
            ],
            'marcas' => $marcas,
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'bien'   => 'required',
            'nombre'   => 'required|max:100',
        ]);

        try {

            DB::beginTransaction();

            $marca = new Marca();
            $marca->bien_id = $request->bien;
            $marca->x_nombre = Str::upper($request->nombre);
            $marca->user_id = Auth::user()->id;
            $marca->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Marca se registró con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al crear la Marca, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'bien'   => 'required',
            'nombre'   => 'required|max:100',
        ]);

        try {

            DB::beginTransaction();

            $marca = Marca::findOrFail($request->id);
            $marca->bien_id = $request->bien;
            $marca->x_nombre = Str::upper($request->nombre);
            $marca->user_id = Auth::user()->id;
            $marca->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Marca se actualizó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al actualizar la Marca, intente nuevamente o contacte al Administrador del Sistema. Código de error '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $marca = Marca::findOrFail($request->id);
            $marca->l_activo = 'N';
            $marca->user_id = Auth::user()->id;
            $marca->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Marca se eliminó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al eliminar la Marca, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function modelos(Request $request)
    {
        $modelos = Modelo::where('l_activo', 'S')
        ->where('marca_id', $request->id)
        ->orderBy('x_nombre', 'ASC')
        ->get();

        return $modelos;
    }
}
