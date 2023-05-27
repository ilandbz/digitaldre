<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resolucionpersona extends Model
{
    use HasFactory;
    protected $table = 'inv_resopersonas';
    
    protected $hidden = ['created_at', 'updated_at', 'user_id'];
    public function getPersona()
    {
        return $this->hasOne(Persona::class, 'id', 'persona_id')->select('id',  'x_nombre', 'c_dni');
    }
    public function getResolucion()
    {
        return $this->hasOne(Resolucion::class, 'id', 'resolucion_id');
    }

}
