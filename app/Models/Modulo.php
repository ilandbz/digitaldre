<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;

    protected $table = 'inv_modulos';

    public $timestamps = false;

    public function getMenusAlt()
    {
        return $this->hasMany(Menu::class, 'modulo_id', 'id')->orderBy('modulo_id', 'ASC')->orderBy('n_orden', 'ASC');
    }
    
    public function getMenusActivos()
    {
        return $this->hasMany(Menu::class, 'modulo_id', 'id')->orderBy('modulo_id', 'ASC')->orderBy('n_orden', 'ASC');
    }
    
    public function getMenus()
    {
        return $this->hasMany(Menu::class, 'modulo_id', 'id')->orderBy('modulo_id', 'ASC')->orderBy('n_orden', 'ASC')->with('getSubmenus');
    }
}
