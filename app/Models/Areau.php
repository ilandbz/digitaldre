<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Areau extends Model
{
    use HasFactory;
    protected $table = 'inv_areasu';

    protected $hidden = ['created_at', 'updated_at', 'user_id',];

    public function getDireccionu()
    {
        return $this->hasOne(Direccionu::class, 'id', 'direccion_id');
    }
}

