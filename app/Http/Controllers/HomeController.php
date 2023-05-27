<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Modulo;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $perfil = User::with('getRol')->findOrFail(Auth::user()->id);
        
        return view('home', compact('perfil'));
    }

    public function perfil(Request $request)
    {
        if ($request->password) {
            $this->validate($request, [
                'nombres'   => 'required|max:50',
                'dni'       => 'required|digits:8|unique:inv_users,c_dni,'.Auth::user()->id,
                'email'     => 'required|max:50',
                'username'  => 'required|max:50',
                'password'  => 'required|confirmed|min:6',
            ]);
        } else {
            $this->validate($request, [
                'nombres'   => 'required|max:50',
                'dni'       => 'required|digits:8|unique:inv_users,c_dni,'.Auth::user()->id,
                'email'     => 'required|max:50',
                'username'  => 'required|max:50',
            ]);
        }

        try {

            DB::beginTransaction();

            $user = User::findOrFail(Auth::user()->id);
            $user->x_nombres = Str::upper($request->nombres);
            $user->c_dni = $request->dni;
            $user->x_email = Str::lower($request->email);
            $user->x_telefono = $request->telefono;
            $user->username = Str::lower($request->username);
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'Sus datos se actualizaron con éxito.',
                'user'      =>  $user
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al actualizar sus Datos, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }
}
