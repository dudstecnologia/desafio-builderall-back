<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'buyer_email',
        'status'
    ];

    public function cart()
    {
    	return $this->hasOne(Cart::class);
    }

    public function payment()
    {
    	return $this->hasOne(Payment::class);
    }
}
