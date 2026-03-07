<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    protected $table = 'checkouts';
    protected $guarded = ['id'];

    public function details()
    {
        $this->hasMany(CheckoutDetail::class);
    }

    public function transaction()
    {
        $this->hasOne(Transaction::class);
    }

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
