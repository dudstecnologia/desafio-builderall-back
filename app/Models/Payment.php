<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'gateway',
        'transaction_code',
        'purchase_id'
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
