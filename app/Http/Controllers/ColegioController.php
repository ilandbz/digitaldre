<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Submenu;
use App\Models\RolSubmenu;
use App\Models\User;
use App\Models\Cargo;
use App\Models\Unidad;
use App\Models\Direccionu;
use App\Models\Dependencia;
use App\Models\Areau;
use App\Models\Colegio;

class ColegioController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {   $dependencia = Auth::user()->dependencia_id;
         
        $submenu = Submenu::where('x_route', Route::currentRouteName())->pluck('id')->first();
        if ($opcion = RolSubmenu::where('rol_id', Auth::user()->rol_id)->where('submenu_id', $submenu)->where('l_activo', 'S')->first()) 
        { 
                if ($dependencia == 1)
                {
                    $colegios = Colegio::all()->where('l_activo', 'S');
                   
                }
                else 
                {
                    $colegios = Colegio::all()->where('dependencia_id', $dependencia);
                    //$colegios = Colegio::all()->where('l_activo', 'S')->where('dependencia_id', $dependencia);
                }
       
         return view('sistema.reportes.colegios')->with('colegios',$colegios);
        } else {
            return redirect('home');
        }
    }

    
    public function buscar(Request $request)
    {
        /*$dependencia = Auth::user()->dependencia_id;
 
        if ($dependencia == 1)
        {
            $$colegios = Colegio::where('l_activo', 'S');
            // $ugeles = Ugel::where('l_activo', 'S')->where('dependencia_id', $dependencia);
        }
        else 
        {
            $colegios = Colegio::where('l_activo', 'S')->where('dependencia_id', $dependencia);
        }*/
       //$colegios = Colegio::where('l_activo', 'S');

    }

    
}
