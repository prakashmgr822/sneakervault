<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'quantity', 'user_id', 'product'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }




}
