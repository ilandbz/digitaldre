<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $table = 'inv_areas';

    protected $hidden = ['created_at', 'updated_at', 'user_id',];

    public function getDireccion()
    {
        return $this->hasOne(Direccion::class, 'id', 'direccion_id');
    }
}
