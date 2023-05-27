<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccionu extends Model
{
    use HasFactory;
    protected $table = 'inv_direccionues';

    protected $hidden = [
        'created_at', 'updated_at', 'user_id',
    ];
    public function getAreasu()
    {
        return $this->hasMany(Areau::class, 'direccion_id', 'id')->where('l_activo', 'S');
    }
}
