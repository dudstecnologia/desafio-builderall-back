<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'total',
        'purchase_id'
    ];

    public function cartProducts()
    {
    	return $this->hasMany(CartProduct::class);
    }
}
