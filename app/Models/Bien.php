<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bien extends Model
{
    use HasFactory;

    protected $table = 'inv_bienes';

    protected $hidden = [
        'created_at', 'updated_at', 'user_id',
    ];

    public function getGrupo()
    {
        return $this->hasOne(Grupo::class, 'id', 'grupo_id');
    }
    
    public function getClase()
    {
        return $this->hasOne(Clase::class, 'id', 'clase_id');
    }
}
