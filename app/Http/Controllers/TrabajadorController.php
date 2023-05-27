<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Submenu;
use App\Models\RolSubmenu;
use App\Models\Trabajador;
use App\Models\User;
use App\Models\Cargo;
use App\Models\Unidad;
use App\Models\Direccion;
use App\Models\Area;
use App\Models\Persona;



class TrabajadorController extends Controller
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
            $direcciones = Direccion::select('id','x_direcciones')->where('l_activo', 'S')->orderBy('id')->get();
            $areas = Area::select('id','x_areas')->where('l_activo', 'S')->orderBy('id')->get();
            $personas = Persona::where('l_activo', 'S')->orderBy('id')->get();

            return view('sistema.administrador.trabajadores', compact('opcion','cargos','unidades','direcciones','areas','personas'));
        } 
        else 
        {
            return redirect('home');
        }
    }

    public function buscararea(Request $request)//busca areas en el modal editar
    {
            $areas = Area::where("direccion_id", $request->id)->get();
           return $areas;
    }


    public function buscar(Request $request)
    {   
       // $trabajadores = Trabajador::with('getCargo','getUnidad','getDireccion','getArea');
         
       $dependencia = Auth::user()->dependencia_id;
 
        if ($dependencia == 1)
        {
            $trabajadores = Trabajador::where('l_activo', 'S')->where('dependencia_id', $dependencia);
        }
        else 
        {
            $trabajadores = Trabajador::where('l_activo', 'S')->where('dependencia_id', $dependencia);
        }

        if ($request->search) {
            $search = $request->search;

            switch ($request->filter) 
            {
                case 1:
                    $trabajadores->where(function ($query) use ($search){
                        $query->where('x_nombres', 'LIKE', "%{$search}%")
                        ->orWhere('id', 'LIKE', "%{$search}%");
                    });
                    break;
                case 2:
                    
                    $trabajadores->where(function ($query) use ($search){
                        $query->where('cargo_id', $search);
                    });
                    break;
                default:
                    $trabajadores->where(function ($query) use ($search){
                        $query->where('area_id', $search);
                    });
                    break;
            }
        }

       $trabajadores = $trabajadores->with('getCargo','getUnidad','getDireccion','getArea','getPersona')->orderBy('id', 'ASC')->paginate(10);
       // $trabajadores = $trabajadores->orderBy('x_nombres', 'ASC')->paginate(10);
       
        return [
            'pagination' => [
                'total' => $trabajadores->total(),
                'current_page' => $trabajadores->currentPage(),
                'per_page' => $trabajadores->perPage(),
                'last_page' => $trabajadores->lastPage(),
                'from' => $trabajadores->firstItem(),
                'to' => $trabajadores->lastPage(),
                'index' => ($trabajadores->currentPage()-1)*$trabajadores->perPage(),
            ],
            'trabajadores' => $trabajadores,
        ];
        
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            //'nombre'        => 'required|max:150',
            //'dni'           => 'required|digits:8',
            'telefono2'      => 'required|max:9',
            'email2'         => 'required|email|max:50',
            'cargo'         => 'required',
            'unidad'        => 'required',
            'direccion'     => 'required',
            'area'          => 'required',
            'tipopersonal'  => 'required|max:30',

        ]);

        try {

            DB::beginTransaction();
            $dependencia = Auth::user()->dependencia_id;

            $trabajador = new Trabajador();
           // $trabajador->x_nombres = Str::upper($request->nombre);
            $trabajador->persona_id = $request->persona;
           // $trabajador->c_dni = $request->dni;
           // $trabajador->x_telefono = $request->telefono;
            $trabajador->x_telefono2 = $request->telefono2;
            $trabajador->cargo_id = $request->cargo;
            $trabajador->unidad_id = $request->unidad;
            $trabajador->direccion_id = $request->direccion;
            $trabajador->area_id = $request->area;
           // $trabajador->x_email = $request->email;
            $trabajador->x_email2 = $request->email2;
            $trabajador->dependencia_id= $dependencia;
            $trabajador->x_tipopersonal = $request->tipopersonal;
            $trabajador->user_id = Auth::user()->id;
            $trabajador->save();

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
            //'nombre'        => 'required|max:150',
            //'dni'           => 'required|digits:8',
            'telefono2'      => 'required|max:9',
            'email2'         => 'required|email|max:50',
            'cargo'         => 'required',
            'unidad'        => 'required',
            'direccion'     => 'required',
            'area'          => 'required',
            'tipopersonal'  => 'required|max:30',
        ]);

        try {

            DB::beginTransaction();
            $dependencia = Auth::user()->dependencia_id;

            $trabajador = Trabajador::findOrFail($request->id);
            //$trabajador->x_nombres = Str::upper($request->nombre);
            $trabajador->persona_id = $request->persona;
            //$trabajador->c_dni = $request->dni;
            //$trabajador->x_telefono = $request->telefono;
            $trabajador->x_telefono2 = $request->telefono2;
            $trabajador->cargo_id = $request->cargo;
            $trabajador->unidad_id = $request->unidad;
            $trabajador->direccion_id = $request->direccion;
            $trabajador->area_id = $request->area;
           // $trabajador->x_email = $request->email;
            $trabajador->x_email2 = $request->email2;
            $trabajador->dependencia_id= $dependencia;
            $trabajador->x_tipopersonal = $request->tipopersonal;
            $trabajador->user_id = Auth::user()->id;
            $trabajador->save();



           
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

            $trabajador = Trabajador::findOrFail($request->id);
            $trabajador->l_activo = 'N';
            $trabajador->user_id = Auth::user()->id;
            $trabajador->save();

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
