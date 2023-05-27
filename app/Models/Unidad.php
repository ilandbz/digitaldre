<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    use HasFactory;
    protected $table = 'inv_unidades';

    protected $hidden = [
        'created_at', 'updated_at', 'user_id',
    ];
}
