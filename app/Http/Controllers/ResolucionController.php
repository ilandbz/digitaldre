<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Submenu;
use App\Models\RolSubmenu;
use App\Models\User;
use App\Models\Dependencia;
use App\Models\Resoluciontipo;
use App\Models\Persona;
use App\Models\Resolucion;



class ResolucionController extends Controller
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
            $resoluciontipos = Resoluciontipo::where('l_activo', 'S')->orderBy('id')->get();
            $dependencias = Dependencia::where('l_activo', 'S')->orderBy('id')->get();
            $personas = Persona::where('l_activo', 'S')->orderBy('id')->get();
            
            return view('sistema.tablas.resoluciones', compact('opcion','resoluciontipos','personas','dependencias'));
        } 
        else 
        {
            return redirect('home');
        }
    }


    public function buscar(Request $request)
    {   
        $usuario = User::find(Auth::user()->id);
        $resoluciones = Resolucion::where('l_activo', 'S')->where('dependencia_id', $usuario->dependencia_id);
        

        if ($request->search) {
            $search = $request->search;

            switch ($request->filter) 
            {
                case 1:
                    $resoluciones->where(function ($query) use ($search){
                        $query->where('x_numero', 'LIKE', "%{$search}%")
                        ->orWhere('id', 'LIKE', "%{$search}%");
                    });
                    break;/*
                case 2:
                    
                    $resoluciones->where(function ($query) use ($search){
                        $query->where('resoluciontipo_id', $search);
                    });
                    break;*/

            }
        }

       $resoluciones = $resoluciones->with('getResoluciontipo','getPersona','getDependencia')->orderBy('id', 'ASC')->paginate(10);
      // $resoluciones = $resoluciones->with('getResoluciontipo','getPersona')->orderBy('id', 'ASC')->paginate(10);
       
        return [
            'pagination' => [
                'total' => $resoluciones->total(),
                'current_page' => $resoluciones->currentPage(),
                'per_page' => $resoluciones->perPage(),
                'last_page' => $resoluciones->lastPage(),
                'from' => $resoluciones->firstItem(),
                'to' => $resoluciones->lastPage(),
                'index' => ($resoluciones->currentPage()-1)*$resoluciones->perPage(),
            ],
            'resoluciones' => $resoluciones,
        ];
        
    }
   /*Public función Ver_archivo()
    {
         Storage::response(ArchivosPDF/archivo);
    }*/
    public function store(Request $request)
    {
        $this->validate($request, [
           
            'numero'         => 'required|max:15',
            'resoluciontipo' => 'required',
            'visto'          => 'required',
            'asunto'         => 'required|max:150',
            'estado'         => 'required',
            'dependencia'    => 'required',
        ]);

        try {
            
            DB::beginTransaction();
            $resolucion = new Resolucion();
            $resolucion->x_estado = $request->estado;
            $resolucion->resoluciontipo_id = $request->resoluciontipo;
            $resolucion->dependencia_id = $request->dependencia;
            $resolucion->x_numero = $request->numero;
            $resolucion->x_visto = $request->visto;
            $resolucion->x_fecha = $request->fecha;
            $resolucion->x_asunto = $request->asunto;
            $resolucion->user_id = Auth::user()->id;
            $resolucion->x_enviado = 'N';

            $file=$request->file('archivo');
            $filename=$file->getClientOriginalName();
            $extension=$file->getClientOriginalExtension();
            $fileContent=time().'-'.Str::random(10).'.'.$extension;
            $route='ArchivosPDF';
            Storage::putFileAs('public/'.$route, $file, $fileContent);
           
            $resolucion->x_archivo=$fileContent;
            $resolucion->save();
            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Resolución se registró con éxito.',
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
            'numero'         => 'required|max:15',
            'resoluciontipo' => 'required',      
            'asunto'         => 'required|max:150',
            'visto'          => 'required',
            'estado'         => 'required',
        ]);

        try {

            DB::beginTransaction();
        
            $resolucion = Resolucion::findOrFail($request->id);
        
            $resolucion->x_estado = $request->estado;
            $resolucion->resoluciontipo_id = $request->resoluciontipo;
            $resolucion->dependencia_id = $request->dependencia;
            $resolucion->x_numero = $request->numero;
            $resolucion->x_fecha = $request->fecha;
            $resolucion->x_visto = $request->visto;
            $resolucion->x_asunto = $request->asunto;
            $resolucion->user_id = Auth::user()->id;
        
            /*$file=$request->file('archivo');
            $filename=$file->getClientOriginalName();
            $extension=$file->getClientOriginalExtension();
            $fileContent=time().'-'.Str::random(10).'.'.$extension;
            $route='ArchivosPDF';
            Storage::putFileAs('public/'.$route, $file, $fileContent);
           
            $resolucion->x_archivo=$fileContent;*/
            $resolucion->save();

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
                'message'   =>  'Ocurrio un error al actualizar el Trabajador, intente nuevamente o contacte al Administrador del Sistema. Código de error '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $resolucion = Resolucion::findOrFail($request->id);
            $resolucion->l_activo = 'N';
            $resolucion->user_id = Auth::user()->id;
            $resolucion->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Resolución se eliminó con éxito.',
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
    public function enviar(Request $request)
    {
        $this->validate($request, [
            'numero'         => 'required|max:15',
            'resoluciontipo' => 'required',
            'asunto'         => 'required|max:150',
            'estado'         => 'required',
        ]);

        try {

            DB::beginTransaction();
        
            DB::beginTransaction();
        
            $resolucion = Resolucion::findOrFail($request->id);
            $resolucion->persona_id = $request->persona;
           
            /*$resolucion->x_numero = $request->numero;
            $resolucion->x_fecha = $request->fecha;
            $resolucion->x_asunto = $request->asunto;*/
            $resolucion->x_enviado = 'S';
            $resolucion->save();

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
                'message'   =>  'Ocurrio un error al actualizar el Trabajador, intente nuevamente o contacte al Administrador del Sistema. Código de error '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

   
}
