<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Submenu;
use App\Models\RolSubmenu;
use App\Models\Persona;

class PersonaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $submenu = Submenu::where('x_route', Route::currentRouteName())->pluck('id')->first();
        
        if ($opcion = RolSubmenu::where('rol_id', Auth::user()->rol_id)->where('submenu_id', $submenu)->where('l_activo', 'S')->first()) {
            return view('sistema.administrador.personas', compact('opcion'));
        } else {
            return redirect('home');
        }
    }
    public function buscar(Request $request)
    {
        $personas = Persona::where('l_activo', 'S');

        if ($request->search) {
            $search = $request->search;
            
            switch ($request->filter) {
                case 1:
                    $personas->where(function ($query) use ($search){
                        $query->where('x_nombre', 'LIKE', "%{$search}%")
                        ->orWhere('c_dni', 'LIKE', "%{$search}%");;
                    });
                    break;
            }
        }

        $personas = $personas->orderBy('id', 'ASC')->paginate(10);

        return [
            'pagination' => [
                'total' => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page' => $personas->perPage(),
                'last_page' => $personas->lastPage(),
                'from' => $personas->firstItem(),
                'to' => $personas->lastPage(),
                'index' => ($personas->currentPage()-1)*$personas->perPage(),
            ],
            'personas' => $personas,
        ];
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            
            'nombre'        => 'required|max:150',
            'dni'           => 'required|digits:8',
            'telefono'      => 'required|max:9',
            'email'         => 'required|email|max:50',
        ]);

        try {

            DB::beginTransaction();

            $persona = new Persona();
            $persona->x_nombre = Str::upper($request->nombre);
            $persona->c_dni = $request->dni;
            $persona->x_telefono = $request->telefono;
            $persona->x_email =Str::lower( $request->email);
            $persona->user_id = Auth::user()->id;
            $persona->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Persona se registró con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al crear la Persona, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            
            'nombre'        => 'required|max:150',
            'dni'           => 'required|digits:8',
            'telefono'      => 'required|max:9',
            'email'         => 'required|email|max:50',
        ]);

        try {

            DB::beginTransaction();

            $persona = Persona::findOrFail($request->id);
            $persona->x_nombre = Str::upper($request->nombre);
            $persona->c_dni = $request->dni;
            $persona->x_telefono = $request->telefono;
            $persona->x_email = $request->email;
            $persona->user_id = Auth::user()->id;
            $persona->save();
            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Persona se actualizó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al actualizar la Persona, intente nuevamente o contacte al Administrador del Sistema. Código de error '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }
    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $persona = Persona::findOrFail($request->id);
            $persona->l_activo = 'N';
            $persona->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'La Persona se eliminó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al eliminar la Persona, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }
}
