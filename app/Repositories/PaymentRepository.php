<?php

namespace App\Repositories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class PaymentRepository extends BaseRepository
{
    protected $model = Payment::class;

    /**
     * Return payment by Transaction Code
     *
     * @return model|null
     */
    public function getPaymentByTransactionCode($transaction_code): ?Model
    {
        try {
            return $this->model->whereTransactionCode($transaction_code)->first();
        } catch (Throwable $th) {
            return $this->writeLog($th);
        }
    }
}