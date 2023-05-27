<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $table = 'inv_modelos';

    protected $hidden = [
        'created_at', 'updated_at', 'user_id',
    ];

    public function getBien()
    {
        return $this->hasOne(Bien::class, 'id', 'bien_id');
    }
    public function getPersona()
    {
        return $this->hasOne(Persona::class, 'id', 'persona_id');
    }
}
