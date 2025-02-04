<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Vendor extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'description', 'rating', 'total_sales', 'phone'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
