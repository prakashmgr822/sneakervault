<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
        'subtotal', 'tax', 'shipping_cost', 'total', 'status', 'shipping_address', 'user_id', 'pidx', 'transaction_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(['size', 'quantity']) // Include pivot attributes
            ->withTimestamps();
    }

}
