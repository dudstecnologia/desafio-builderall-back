<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paypal extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'client_id',
        'secret',
        'mode'
    ];

    const SANDBOX = 0;
    const LIVE = 1;

    const MODES = [
        self::SANDBOX => 'Sandbox',
        self::LIVE => 'Live'
    ];
}
