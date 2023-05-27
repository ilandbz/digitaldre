<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $table = 'inv_marcas';

    protected $hidden = [
        'created_at', 'updated_at', 'user_id',
    ];

    public function getBien()
    {
        return $this->hasOne(Bien::class, 'id', 'bien_id');
    }
    
    public function getModelos()
    {
        return $this->hasMany(Modelo::class, 'marca_id', 'id')->where('l_activo', 'S')->orderBy('x_nombre');
    }
}
