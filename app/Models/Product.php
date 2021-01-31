<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\UploadService;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'quantity',
        'image'
    ];

    protected $appends = [
        'image_url'
    ];

    public function getImageUrlAttribute()
    {
        return UploadService::getUrl($this->attributes['image']);
    }
}
