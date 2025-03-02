<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Vendor extends Authenticatable implements HasMedia
{
    use InteractsWithMedia, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'description', 'rating', 'total_sales', 'phone'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, Product::class);
    }

    function getImageUrlAttribute(){
        return $this->getFirstMediaUrl();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        try {
            $this->addMediaConversion('thumb')
                ->width(160)
                ->height(300);

            $this->addMediaConversion('medium')
                ->width(320)
                ->height(320);

        } catch (InvalidManipulation $e) {

        }
    }

    function getImage($collectionName = null)
    {
        if ($collectionName) {
            if (count($this->getMedia($collectionName)) == 0) return null;
            return $this->getFirstMediaUrl($collectionName ?? null);
        } else {
            if (count($this->getMedia()) == 0) return null;
            return $this->getFirstMediaUrl() ?? null;
        }
    }

    function getImageByIndex($index = 0)
    {
        if (count($this->getMedia()) == 0) return null;
//        if ($this->getMedia()->first()->getUrl('large') != null)
        try {
            return $this->getMedia()[$index]->getUrl() ?? null;
        } catch (\Exception $e) {
            return null;
        }
        return $this->getFirstMediaUrl() ?? null;
    }

    function getThumbnail($collectionName = null)
    {
        if ($collectionName) {
            if (count($this->getMedia($collectionName)) == 0) return null;
            return $this->getMedia($collectionName)->first()->getUrl('medium') ?? null;
        } else {
            if (count($this->getMedia()) == 0) return null;
            return $this->getMedia()->first()->getUrl('medium') ?? null;
        }
    }
}
