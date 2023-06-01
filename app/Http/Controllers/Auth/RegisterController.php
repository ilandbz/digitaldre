<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index(){
        $this->middleware('guest');
        return view('auth.register');
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(Request $request)
    {
        $this->validate($request, [
            'c_dni'     => 'unique:inv_personas,c_dni',
            'x_nombre'  => 'unique:inv_personas,x_nombre|required|max:100',
            'x_email'   => 'unique:inv_personas,x_email|email',
            'password'  => 'required|min:5|confirmed'
        ], [
            'required' => 'El campo es requerido.',
            'unique' => 'Ya existe el registro con el mismo nombre de campo',
            'confirmed' => 'La clave no coincide'
        ]);


        Persona::create([
            'c_dni' => $request->c_dni, 
            'x_nombre' => $request->x_nombre,
            'x_telefono' => $request->x_telefono,
            'x_email' => $request->x_email,
            'x_tipo' => 'VISITANTE',
            'l_activo' => 'S',
            'user_id' => 1,
            'password' => Hash::make($request->password)
        ]);
        return redirect('login')->with('success', '¡Registro creado correctamente!');

    }
}
