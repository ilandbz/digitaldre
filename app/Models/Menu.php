<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'inv_menus';

    public $timestamps = false;    

    public function getRolMenu()
    {
        return $this->hasOne(RolSubmenu::class, 'menu_id', 'id')->where('l_activo', 'S')->groupBy('menu_id');
    }

    public function getSubmenus()
    {
        return $this->hasMany(Submenu::class, 'menu_id', 'id')->orderBy('n_orden', 'ASC');
    }
    
    public function getSubmenusActivos()
    {
        return $this->hasMany(Submenu::class, 'menu_id', 'id')->orderBy('n_orden', 'ASC')->with('getRol');
    }
}
