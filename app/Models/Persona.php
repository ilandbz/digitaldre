<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'inv_personas';

    protected $fillable = [
        'id','c_dni', 'x_nombre', 'x_telefono', 'x_email', 'x_tipo' ,'l_activo', 'user_id', 'password'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'user_id',
    ];
}
