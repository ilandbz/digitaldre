<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submenu extends Model
{
    use HasFactory;

    protected $table = 'inv_submenus';
    
    public $timestamps = false;

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function getRol()
    {
        return $this->hasOne(RolSubmenu::class, 'submenu_id', 'id')->where('l_activo', 'S');
    }
}
