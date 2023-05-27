<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    use HasFactory;
    protected $table = 'inv_direcciones';

    protected $hidden = [
        'created_at', 'updated_at', 'user_id',
    ];
    public function getAreas()
    {
        return $this->hasMany(Area::class, 'direccion_id', 'id')->where('l_activo', 'S');
    }
}
