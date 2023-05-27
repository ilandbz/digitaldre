<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;
    protected $table = 'inv_cargos';

    protected $hidden = [
        'created_at', 'updated_at', 'user_id',
    ];
   
}
