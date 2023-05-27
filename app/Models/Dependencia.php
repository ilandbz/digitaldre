<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependencia extends Model
{
    use HasFactory;

    protected $table = 'inv_dependencias';

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function getSede()
    {
        return $this->hasOne(Sede::class, 'id', 'sede_id');
    }
}
