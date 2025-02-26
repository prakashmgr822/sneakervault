<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends BaseModel
{
    use InteractsWithMedia;

    protected $guarded = ['id'];
    protected $appends = ['image_url'];
    protected $casts = ['specifications' => 'array', 'product_sizes' => 'array'];

    protected $fillable = [
        'name', 'description', 'brand', 'price', 'stock_quantity', 'specifications', 'sizes', 'product_sizes'
    ];

    function getImageUrlAttribute(){
        return $this->getFirstMediaUrl();
    }



    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

}
