<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'cart_data', 'address'];

    protected $casts = [
        'cart_data' => 'array', // Convert JSON to array automatically
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }




}
