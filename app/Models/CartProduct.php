<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartProduct extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'image',
        'price',
        'quantity'
    ];
}
