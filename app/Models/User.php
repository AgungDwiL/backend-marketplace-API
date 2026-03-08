<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $table = 'users';
    protected $guarded = [
        'id',
        'balance',
        'updated_at',
        'created_at',
    ];
}
