<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resolucion extends Model
{
   use HasFactory;
    protected $table = 'inv_resoluciones';
    
    protected $hidden = ['created_at', 'updated_at', 'user_id',];

    public function getResoluciontipo()
    {
        return $this->hasOne(Resoluciontipo::class, 'id', 'resoluciontipo_id')->select('id',  'x_resoluciontipos');
    }
    public function getPersona()
    {
        return $this->hasOne(Persona::class, 'id', 'persona_id');
    }
    public function getDependencia()
    {
        return $this->hasOne(Dependencia::class, 'id', 'dependencia_id')->select('id',  'x_nombre');
    }
   
}
