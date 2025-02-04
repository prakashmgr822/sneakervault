<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends BaseModel
{
    use InteractsWithMedia;

    protected $guarded = ['id'];
    protected $appends = ['image_url'];

    protected $fillable = [
        'name', 'description', 'brand', 'size', 'price', 'stock_quantity',
    ];

    function getImageUrlAttribute(){
        return $this->getFirstMediaUrl();
    }



    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
