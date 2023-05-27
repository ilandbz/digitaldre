<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    use HasFactory;
    protected $table = 'inv_trabajadores';
    
    protected $hidden = ['created_at', 'updated_at', 'user_id',];
    
    public function getCargo()
    {
        return $this->hasOne(Cargo::class, 'id', 'cargo_id');
    }
    public function getUnidad()
    {
        return $this->hasOne(Unidad::class, 'id', 'unidad_id');
    }
    public function getDireccion()
    {
        return $this->hasOne(Direccion::class, 'id', 'direccion_id')->select('id',  'x_direcciones');
    }
    public function getArea()
    {
        return $this->hasOne(Area::class, 'id', 'area_id')->select('id',  'x_areas');
    }
    public function getPersona()
    {
        return $this->hasOne(Persona::class, 'id', 'persona_id');
    }
}
