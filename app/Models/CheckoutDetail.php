<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutDetail extends Model
{
    protected $table = "checkout_details";
    protected $guarded = ['id'];

    public function product()
    {
        $this->belongsTo(Product::class);
    }

    public function checkout()
    {
        $this->belongsTo(Checkout::class);
    }
}
