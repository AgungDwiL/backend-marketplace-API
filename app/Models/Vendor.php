<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = "vendors";
    protected $guarded = ['id'];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function products()
    {
        $this->hasMany(Product::class);
    }
}
