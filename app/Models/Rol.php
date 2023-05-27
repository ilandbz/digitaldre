<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'inv_roles';

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function getUsers()
    {
        return $this->hasMany(User::class, 'rol_id', 'id');
    }
}
