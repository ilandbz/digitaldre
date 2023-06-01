<?php

namespace App;

use Illuminate\Contracts\Auth\Authenticatable;

class AuthenticatablePersona implements Authenticatable
{
    protected $persona;

    public function __construct(Persona $persona)
    {
        $this->persona = $persona;
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->persona->id;
    }

    public function getAuthPassword()
    {
        return $this->persona->password;
    }

    // Implementa los demás métodos requeridos por la interfaz Authenticatable
}
