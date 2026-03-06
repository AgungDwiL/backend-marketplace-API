<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $guarded = ['id'];

    public function buyer()
    {
        $this->belongsTo(User::class, 'buyer_id', 'id');
    }

    public function vendor()
    {
        $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function checkout()
    {
        $this->belongsTo(Checkout::class);
    }
}
