<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Modulo;
use App\Models\Menu;
use App\Models\Submenu;
use App\Models\RolSubmenu;
use App\Models\Rol;
use App\Models\User;

class RolController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $submenu = Submenu::where('x_route', Route::currentRouteName())->pluck('id')->first();
        
        if ($opcion = RolSubmenu::where('rol_id', Auth::user()->rol_id)->where('submenu_id', $submenu)->where('l_activo', 'S')->first()) {
            return view('sistema.administrador.roles', compact('opcion'));
        } else {
            return redirect('home');
        }
    }

    public function buscar(Request $request)
    {
        $roles = Rol::where('l_activo', 'S')->orderBy('x_nombre', 'ASC');

        if ($request->search) {
            $search = $request->search;
            
            $roles->where(function ($query) use ($search){
                $query->where('x_nombre', 'LIKE', "%{$search}%");
            });
        }

        $roles = $roles->withCount('getUsers')->paginate(10);

        return [
            'pagination' => [
                'total' => $roles->total(),
                'current_page' => $roles->currentPage(),
                'per_page' => $roles->perPage(),
                'last_page' => $roles->lastPage(),
                'from' => $roles->firstItem(),
                'to' => $roles->lastPage(),
                'index' => ($roles->currentPage()-1)*$roles->perPage(),
            ],
            'roles' => $roles,
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre'   => 'required|max:100|unique:inv_roles,x_nombre',
        ]);

        try {

            DB::beginTransaction();

            $rol = new Rol();
            $rol->x_nombre = Str::upper($request->nombre);
            $rol->save();

            foreach ($request->permisos as $modulo) {
                foreach ($modulo['get_menus'] as $menu) {
                    foreach ($menu['get_submenus'] as $submenu) {
                        if (($submenu['ver'] == 'S')) {
                            $permiso = new RolSubmenu();
                            $permiso->rol_id = $rol->id;
                            $permiso->menu_id = $menu['id'];
                            $permiso->submenu_id = $submenu['id'];
                            $permiso->l_crear = $submenu['crear'];
                            $permiso->l_editar = $submenu['editar'];
                            $permiso->l_eliminar = $submenu['eliminar'];
                            $permiso->l_otros = 'N';
                            $permiso->l_activo = 'S';
                            $permiso->save();
                        }
                    }
                }
            }

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Rol se registró con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al crear el Rol, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'nombre'   => 'required|max:100|unique:inv_roles,x_nombre,'.$request->id,
        ]);

        try {

            DB::beginTransaction();

            $rol = Rol::findOrFail($request->id);
            $rol->x_nombre = Str::upper($request->nombre);
            $rol->save();

            foreach ($request->permisos as $modulo) {
                foreach ($modulo['get_menus'] as $menu) {
                    foreach ($menu['get_submenus'] as $submenu) {
                        if ($submenu['permiso_id']) {
                            if (($submenu['ver'] == 'S')) {
                                $permiso = RolSubmenu::find($submenu['permiso_id']);
                                /*$permiso->rol_id = $rol->id;
                                $permiso->menu_id = $menu['id'];
                                $permiso->submenu_id = $submenu['id'];*/
                                $permiso->l_crear = $submenu['crear'];
                                $permiso->l_editar = $submenu['editar'];
                                $permiso->l_eliminar = $submenu['eliminar'];
                                $permiso->l_otros = 'N';
                                $permiso->l_activo = 'S';
                                $permiso->save();
                            } else {
                                $permiso = RolSubmenu::destroy($submenu['permiso_id']);
                            }
                        } else {
                            if (($submenu['ver'] == 'S')) {
                                $permiso = new RolSubmenu();
                                $permiso->rol_id = $rol->id;
                                $permiso->menu_id = $menu['id'];
                                $permiso->submenu_id = $submenu['id'];
                                $permiso->l_crear = $submenu['crear'];
                                $permiso->l_editar = $submenu['editar'];
                                $permiso->l_eliminar = $submenu['eliminar'];
                                $permiso->l_otros = 'N';
                                $permiso->l_activo = 'S';
                                $permiso->save();
                            }
                        }
                    }
                }
            }

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Rol se actualizó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al actualizar el Rol, intente nuevamente o contacte al Administrador del Sistema. Código de error '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $rol = Rol::findOrFail($request->id);
            $rol->l_activo = 'N';
            $rol->save();

            RolSubmenu::where('rol_id', $rol->id)->delete();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El Rol se eliminó con éxito.',
            ];


        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'action'    =>  'error',
                'title'     =>  'Incorrecto!!',
                'message'   =>  'Ocurrio un error al eliminar el Rol, intente nuevamente o contacte al Administrador del Sistema. Código de error: '.$e->getMessage(),
                'error'     =>  'Error: '.$e->getMessage()
            ];
        }
    }

    public function submenus($id)
    {
        $modulos = [];
        $menus = [];
        $submenus = [];

        if ($id > 0) {
            $consulta = Modulo::where('l_activo', 'S')->with([
                'getMenusActivos' => function($query) use($id) {
                    $query->with([
                        'getSubmenusActivos' => function($que) use($id) {
                            $que->with([
                                'getRol' => function($q) use($id) {
                                    $q->where('rol_id', $id);
                                }
                            ]);
                        }
                    ]);
                }
            ])->get();

            foreach ($consulta as $modulo) {

                foreach ($modulo->getMenusActivos as $menu) {
                    
                    foreach ($menu->getSubmenusActivos as $submenu) {
                        if ($submenu->getRol) {
                            $submenus[] = [
                                'id' => $submenu->id,
                                'nombre' => $submenu->x_nombre,
                                'opciones' => $submenu->l_opciones,
                                'activo' => $submenu->l_activo,
                                'ver' => $submenu->l_activo,
                                'crear' => $submenu->getRol->l_crear,
                                'editar' => $submenu->getRol->l_editar,
                                'eliminar' => $submenu->getRol->l_eliminar,
                                'permiso_id' => $submenu->getRol->id
                            ];
                        } else {
                            $submenus[] = [
                                'id' => $submenu->id,
                                'nombre' => $submenu->x_nombre,
                                'opciones' => $submenu->l_opciones,
                                'activo' => $submenu->l_activo,
                                'ver' => 'N',
                                'crear' => 'N',
                                'editar' => 'N',
                                'eliminar' => 'N',
                                'permiso_id' => null
                            ];
                        }
                    }

                    $menus[] = [
                        'id' => $menu->id,
                        'nombre' => $menu->x_nombre,
                        'submenus' => $menu->l_submenus,
                        'get_submenus' => $submenus
                    ];
                    $submenus = [];
                }
                
                $modulos[] = [
                    'nombre' => $modulo->x_nombre,
                    'get_menus' => $menus,
                ];
                $menus = [];
            }
        } else {

            $consulta = Modulo::where('l_activo', 'S')->with(['getMenus'])->get();

            foreach ($consulta as $modulo) {

                foreach ($modulo->getMenus as $menu) {
                    
                    foreach ($menu->getSubmenus as $submenu) {
                        $submenus[] = [
                            'id' => $submenu->id,
                            'nombre' => $submenu->x_nombre,
                            'opciones' => $submenu->l_opciones,
                            'activo' => $submenu->l_activo,
                            'ver' => 'N',
                            'crear' => 'N',
                            'editar' => 'N',
                            'eliminar' => 'N'
                        ];
                    }

                    $menus[] = [
                        'id' => $menu->id,
                        'nombre' => $menu->x_nombre,
                        'submenus' => $menu->l_submenus,
                        'get_submenus' => $submenus
                    ];
                    $submenus = [];
                }
                
                $modulos[] = [
                    'nombre' => $modulo->x_nombre,
                    'get_menus' => $menus,
                ];
                $menus = [];
            }
        }

        return $modulos;
    }
}
