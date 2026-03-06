<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';
    protected $guarded = ['id'];

    public function product()
    {
        $this->belongsTo(Product::class);
    }

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
