<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
        'subtotal', 'tax', 'shipping_cost', 'total', 'status', 'shipping_address', 'user_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
