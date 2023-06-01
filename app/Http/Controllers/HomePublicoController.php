<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use App\Models\Dependencia;
use App\Models\Persona;
use App\Models\Resolucion;
use App\Models\Resolucionpersona;
use App\Models\Resoluciontipo;
use App\Models\RolSubmenu;
use App\Models\Submenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
class HomePublicoController extends Controller
{
    public function index(){
        if (Session::has('c_dni')) {
            $perfil = Persona::find(Session::get('c_dni'));
            return view('home', compact('perfil'));
        } else {
            return redirect('login')->with('error', 'Debe iniciar sesión');
        }
    }
    public function logout()
    {
        Session::flush();
        return redirect('login')->with('success', 'Sesión cerrada exitosamente');
    }
    public function resoluciones(){
        $personas = Persona::where('l_activo', 'S')->orderBy('id')->get();
        $resoluciones = Resolucion::where('l_activo', 'S')->whereYear('x_fecha', '2023')->get();
        return view('sistema.tablas.resolucionespublico',compact('resoluciones','personas'));
    }
    public function buscarresoluciones(Request $request)
    {   
        $resolucionpersonas = Resolucionpersona::where('l_activo', 'S')->
            whereHas('getPersona', function ($query){
                $query->where('c_dni', 'LIKE', Session::get('c_dni'));
            });

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
    public function contratos(){
        echo 'a';
    }
}
