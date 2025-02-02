<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'name', 'email', 'password', 'description', 'rating', 'total_sales'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
