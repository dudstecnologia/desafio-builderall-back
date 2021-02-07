<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'buyer_email',
        'status'
    ];

    const PENDENTE = 0;
    const PROCESSANDO = 1;
    const CONCLUIDO = 2;

    const STATUS = [
        self::PENDENTE => 'Pendente',
        self::PROCESSANDO => 'Processando',
        self::CONCLUIDO => 'Concluído'
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
