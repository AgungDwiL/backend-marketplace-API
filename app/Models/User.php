<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $guarded = [
        'id',
        'balance',
        'updated_at',
        'created_at',
    ];
}
