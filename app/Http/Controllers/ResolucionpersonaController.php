<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Submenu;
use App\Models\RolSubmenu;
use App\Models\User;
use App\Models\Resolucion;
use App\Models\Persona;
use App\Models\Resolucionpersona;


class ResolucionpersonaController extends Controller
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
            $personas = Persona::where('l_activo', 'S')->orderBy('id')->get();
             $resoluciones = Resolucion::where('l_activo', 'S')->whereYear('x_fecha', '2023')->get();
           
        
           Return view('sistema.tablas.resolucionpersonas', compact('opcion','resoluciones','personas'));
        } 
        else 
        {
            return redirect('home');
        }
    }

    public function buscar(Request $request)
    {   
            $resolucionpersonas = Resolucionpersona::where('l_activo', 'S');

        if ($request->search) {
            $search = $request->search;

            switch ($request->filter) 
            {
                case 1: //nombre 
                    $resolucionpersonas->whereHas('getPersona', function ($query) use ($search){
                        $query->where('x_nombre', 'LIKE', '%'.$search.'%');
                    });
                    break;
                case 2: //cod resolucion
                    $resolucionpersonas->where(function ($query) use ($search){
                        $query->where('resolucion_id', $search);
                    });
                    break;
                case 3: //dni
                    $resolucionpersonas->whereHas('getPersona', function ($query) use ($search){
                        $query->where('c_dni', 'LIKE', '%'.$search.'%');
                    });
                    break;            
            }
        }

       $resolucionpersonas = $resolucionpersonas->with('getResolucion','getPersona')->orderBy('id', 'ASC')->paginate(10);
        return [
            'pagination' => [
                'total' => $resolucionpersonas->total(),
                'current_page' => $resolucionpersonas->currentPage(),
                'per_page' => $resolucionpersonas->perPage(),
                'last_page' => $resolucionpersonas->lastPage(),
                'from' => $resolucionpersonas->firstItem(),
                'to' => $resolucionpersonas->lastPage(),
                'index' => ($resolucionpersonas->currentPage()-1)*$resolucionpersonas->perPage(),
            ],
            'resolucionpersonas' => $resolucionpersonas,
        ];  
    }
    public function store(Request $request)
    {
        $this->validate($request, [
           
           'persona'         => 'required',
           'resolucion'      => 'required',

        ]);

        try {

            DB::beginTransaction();
            $resolucionpersonas = new Resolucionpersona();
            $resolucionpersonas->persona_id = $request->persona;
            $resolucionpersonas->resolucion_id= $request->resolucion;
            $resolucionpersonas->user_id = Auth::user()->id;
            $resolucionpersonas->x_enviado = 'N';
            $resolucionpersonas->save();
            DB::commit();
            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Resolución se relacionó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al crear la relación, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }
    public function update(Request $request)
    {
        $this->validate($request, [
        
            'persona'         => 'required',
            'resolucion'        => 'required',
        ]);

        try {

            DB::beginTransaction();
            $resolucionpersonas =Resolucionpersona::findOrFail($request->id);
            $resolucionpersonas->persona_id = $request->persona;
            $resolucionpersonas->resolucion_id= $request->resolucion;
            $resolucionpersonas->user_id = Auth::user()->id;
            $resolucionpersonas->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Resolución se actualizó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al actualizar la relación, intente nuevamente o contacte al Administrador del Sistema. Código de error '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }
    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $resolucionpersonas = Resolucionpersona::findOrFail($request->id);
            $resolucionpersonas->l_activo = 'N';
            $resolucionpersonas->user_id = Auth::user()->id;
            $resolucionpersonas->save();

            DB::commit();
            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Resolución relacionada se eliminó con éxito.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al eliminar la relación, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }
}
