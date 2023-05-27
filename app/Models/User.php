<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'inv_users';

    protected $fillable = [
        'id','nombres', 'dni', 'email', 'username', 'password', 'tipo', 'rol_id','activo',
    ];

    protected $hidden = [
        'password', 'created_at', 'updated_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRol()
    {
        return $this->hasOne(Rol::class, 'id', 'rol_id');
    }
    
    public function getSede()
    {
        return $this->hasOne(Sede::class, 'id', 'sede_id')->select('id', 'c_codigo', 'x_nombre');
    }
    
    public function getDependencia()
    {
        return $this->hasOne(Dependencia::class, 'id', 'dependencia_id')->select('id', 'c_codigo', 'x_nombre');
    }
}
