<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Submenu;
use App\Models\RolSubmenu;
use App\Models\Sede;
use App\Models\Dependencia;
use App\Models\Rol;
use App\Models\User;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $submenu = Submenu::where('x_route', Route::currentRouteName())->pluck('id')->first();
        
        if ($opcion = RolSubmenu::where('rol_id', Auth::user()->rol_id)->where('submenu_id', $submenu)->where('l_activo', 'S')->first()) {
            $sedes = Sede::select('id', 'c_codigo', 'x_nombre')
            ->where('l_activo', 'S')
            ->orderBy('c_codigo')
            ->get();
            
            $dependencias = Dependencia::select('id', 'c_codigo', 'x_nombre')
            ->where('l_activo', 'S')
            ->orderBy('c_codigo')
            ->get();

            $roles = Rol::select('id', 'x_nombre')
            ->where('l_activo', 'S')
            ->get();

            return view('sistema.administrador.usuarios', compact('opcion', 'sedes', 'dependencias', 'roles'));
        } else {
            return redirect('home');
        }
    }

    public function buscar(Request $request)
    {
        $dependencia = Auth::user()->dependencia_id;
 
        if ($dependencia == 1)
        {
            $usuarios = User::where('l_activo', 'S');
        }
        else 
        {
            $usuarios = User::where('l_activo', 'S')->where('dependencia_id', $dependencia);
        }
        

        if ($request->search) {
            $search = $request->search;
            
            switch ($request->filter) {
                case 1:
                    $usuarios->where(function ($query) use ($search){
                        $query->where('x_nombres', 'LIKE', "%{$search}%")
                        ->orWhere('x_email', 'LIKE', "%{$search}%")
                        ->orWhere('c_dni', 'LIKE', "%{$search}%");
                    });
                    break;
                case 1:
                    $usuarios->where(function ($query) use ($search){
                        $query->where('perfil', $search);
                    });
                    break;
                
                default:
                    $usuarios->where(function ($query) use ($search){
                        $query->where('dependencia_id', $search);
                    });
                    break;
            }
        }
        $usuarios = $usuarios->with('getRol', 'getSede', 'getDependencia')->orderBy('id', 'ASC')->paginate(10);
       
       // $usuarios = User::with('getRol', 'getSede', 'getDependencia'); original
        return [
            'pagination' => [
                'total' => $usuarios->total(),
                'current_page' => $usuarios->currentPage(),
                'per_page' => $usuarios->perPage(),
                'last_page' => $usuarios->lastPage(),
                'from' => $usuarios->firstItem(),
                'to' => $usuarios->lastPage(),
                'index' => ($usuarios->currentPage()-1)*$usuarios->perPage(),
            ],
            'usuarios' => $usuarios,
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'sede'      => 'required',
            'dependencia'   => 'required',
            'rol'       => 'required',
            'nombres'   => 'required|max:100',
            'dni'       => 'required|digits:8|unique:inv_users,c_dni',
            'email'     => 'required|max:50',
            'username'  => 'required|max:50|unique:inv_users,username',
            'password'  => 'required|min:6',
        ]);

        try {

            DB::beginTransaction();

            $user = new User();
            $user->sede_id = $request->sede;
            $user->dependencia_id = $request->dependencia;
            $user->rol_id = $request->rol;
            $user->x_nombres = Str::upper($request->nombres);
            $user->c_dni = $request->dni;
            $user->x_email = Str::lower($request->email);
            $user->x_telefono = $request->telefono;
            $user->username = Str::lower($request->username);
            $user->password = Hash::make($request->password);
            $user->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El usuario se registró con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al crear el Usuario, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'sede'      => 'required',
            'dependencia'   => 'required',
            'rol'       => 'required',
            'nombres'   => 'required|max:100',
            'dni'       => 'required|digits:8|unique:inv_users,c_dni,'.$request->id,
            'email'     => 'required|max:50',
            'username'  => 'required|max:50|unique:inv_users,username,'.$request->id,
        ]);

        try {

            DB::beginTransaction();

            $user = User::findOrFail($request->id);
            $user->sede_id = $request->sede;
            $user->dependencia_id = $request->dependencia;
            $user->rol_id = $request->rol;
            $user->x_nombres = Str::upper($request->nombres);
            $user->c_dni = $request->dni;
            $user->x_telefono = $request->telefono;
            $user->x_email = Str::lower($request->email);
            $user->username = Str::lower($request->username);
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Usuario se actualizó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al actualizar el Usuario, intente nuevamente o contacte al Administrador del Sistema. Código de error '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $user = User::findOrFail($request->id);
            $user->l_activo = 'N';
            $user->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Usuario se eliminó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al eliminar el Usuario, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }
    
    public function activar(Request $request)
    {
        try {

            DB::beginTransaction();

            $user = User::findOrFail($request->id);
            $user->l_activo = 'S';
            $user->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'Se activo la cuenta del Usuario con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al activar la cuenta del Usuario, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }
}
