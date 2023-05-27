<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colegio extends Model
{
    use HasFactory;
    protected $table = 'vugeles';
    public $timestamps = false;
    

    
    /*public function getCargo()
    {
        return $this->hasOne(Cargo::class, 'id', 'cargo_id');
    }
    public function getUnidad()
    {
        return $this->hasOne(Unidad::class, 'id', 'unidad_id');
    }
    public function getDireccionu()
    {
        return $this->hasOne(Direccionu::class, 'id', 'direccion_id')->select('id',  'x_direcciones');
    }
    public function getAreau()
    {
        return $this->hasOne(Areau::class, 'id', 'area_id')->select('id',  'x_areas');
    }
    public function getDependencia()
    {
        return $this->hasOne(Dependencia::class, 'id', 'dependencia_id')->select('id',  'x_nombre');
    }*/
}
