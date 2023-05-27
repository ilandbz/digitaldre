<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolSubmenu extends Model
{
    use HasFactory;

    protected $table = 'inv_rol_submenus';

    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
