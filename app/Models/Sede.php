<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    use HasFactory;

    protected $table = 'inv_sedes';

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function getDependencias()
    {
        return $this->hasMany(Dependencia::class, 'sede_id', 'id')->where('l_activo', 'S');
    }
}
