<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Submenu;
use App\Models\RolSubmenu;
use App\Models\Ugel;
use App\Models\User;
use App\Models\Cargo;
use App\Models\Unidad;
use App\Models\Direccionu;
use App\Models\Areau;
use App\Models\Persona;

class UgelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $submenu = Submenu::where('x_route', Route::currentRouteName())->pluck('id')->first();

        if ($opcion = RolSubmenu::where('rol_id', Auth::user()->rol_id)->where('submenu_id', $submenu)->where('l_activo', 'S')->first()) 
        {
            $cargos = Cargo::where('l_activo', 'S')->orderBy('id')->get();
            $unidades = Unidad::where('l_activo', 'S')->orderBy('id')->get();
            $direccionues = Direccionu::select('id','x_direcciones')->where('l_activo', 'S')->orderBy('id')->get();
            $areasu = Areau::select('id','x_areas')->where('l_activo', 'S')->orderBy('id')->get();
            $personas = Persona::where('l_activo', 'S')->orderBy('id')->get();

            return view('sistema.tablas.ugeles', compact('opcion', 'cargos','unidades','direccionues','areasu','personas'));
        } 
        else 
        {
            return redirect('home');
        }
    }

    public function buscarareau(Request $request)//busca areas en el modal editar
    {
            $areasu = Areau::where("direccion_id", $request->id)->get();
           return $areasu;
    }


    public function buscar(Request $request)
    {   
        $dependencia = Auth::user()->dependencia_id;
 
        if ($dependencia == 1)
        {
            $ugeles = Ugel::where('l_activo', 'S');
        }
        else 
        {
            $ugeles = Ugel::where('l_activo', 'S')->where('dependencia_id', $dependencia);
        }

        if ($request->search) {
            $search = $request->search;

            switch ($request->filter) 
            {
                case 1:
                    $ugeles->where(function ($query) use ($search){
                        $query->where('x_nombres', 'LIKE', "%{$search}%")
                        ->orWhere('id', 'LIKE', "%{$search}%");
                    });
                    break;
                case 2:
                    
                    $ugeles->where(function ($query) use ($search){
                        $query->where('cargo_id', $search);
                    });
                    break;
                default:
                    $ugeles->where(function ($query) use ($search){
                        $query->where('area_id', $search);
                    });
                    break;
            }
        }

       $ugeles = $ugeles->with('getCargo','getUnidad','getDireccionu','getAreau','getPersona')->orderBy('id', 'ASC')->paginate(10);
       // $trabajadores = $trabajadores->orderBy('x_nombres', 'ASC')->paginate(10);
       
        return [
            'pagination' => [
                'total' => $ugeles->total(),
                'current_page' => $ugeles->currentPage(),
                'per_page' => $ugeles->perPage(),
                'last_page' => $ugeles->lastPage(),
                'from' => $ugeles->firstItem(),
                'to' => $ugeles->lastPage(),
                'index' => ($ugeles->currentPage()-1)*$ugeles->perPage(),
            ],
            'ugeles' => $ugeles,
        ];
        
    }

    public function store(Request $request)
    {
        $this->validate($request, [
           
            'telefono2'      => 'required|max:9',
            'email2'         => 'required|email|max:50',
            'cargo'         => 'required',
            'unidad'        => 'required',
            'direccionu'     => 'required',
            'areau'          => 'required',
            'tipopersonal'  => 'required|max:30',

        ]);

        try {

            DB::beginTransaction();
            $dependencia = Auth::user()->dependencia_id;

            $ugel = new Ugel();
            $ugel->persona_id = $request->persona;
            $ugel->x_telefono2 = $request->telefono2;
            $ugel->cargo_id = $request->cargo;
            $ugel->unidad_id = $request->unidad;
            $ugel->direccion_id = $request->direccionu;
            $ugel->area_id = $request->areau;
            $ugel->x_email2 = $request->email2;
            $ugel->dependencia_id= $dependencia;
            $ugel->x_tipopersonal = $request->tipopersonal;
            $ugel->user_id = Auth::user()->id;
            $ugel->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Trabajador se registró con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al crear el Trabajador, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            
            'telefono2'      => 'required|max:9',
            'email2'         => 'required|email|max:50',
            'cargo'         => 'required',
            'unidad'        => 'required',
            'direccionu'     => 'required',
            'areau'          => 'required',
            'tipopersonal'  => 'required|max:30',
        ]);

        try {

            DB::beginTransaction();
            $dependencia = Auth::user()->dependencia_id;

            $ugel = Ugel::findOrFail($request->id);
            $ugel->persona_id = $request->persona;
            $ugel->x_telefono2 = $request->telefono2;
            $ugel->cargo_id = $request->cargo;
            $ugel->unidad_id = $request->unidad;
            $ugel->direccion_id = $request->direccionu;
            $ugel->area_id = $request->areau;
            $ugel->x_email2 = $request->email2;
            $ugel->dependencia_id= $dependencia;
            $ugel->x_tipopersonal = $request->tipopersonal;
            $ugel->user_id = Auth::user()->id;
            $ugel->save();


            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Trabajador se actualizó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al actualizar el Trabajador, intente nuevamente o contacte al Administrador del Sistema. Código de error '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $ugel = Ugel::findOrFail($request->id);
            $ugel->l_activo = 'N';
            $ugel->user_id = Auth::user()->id;
            $ugel->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Trabajador se eliminó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al eliminar el Trabajador, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }
}
