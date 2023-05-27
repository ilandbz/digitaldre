<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resoluciontipo extends Model
{
    use HasFactory;
    protected $table = 'inv_resoluciontipos';

    protected $hidden = [
        'created_at', 'updated_at', 'user_id',
    ];
}
