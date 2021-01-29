<?php

namespace App\Repositories;

use App\Models\Paypal;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class PaypalRepository extends BaseRepository
{
    protected $model = Paypal::class;

    /**
     * Return paypal data
     *
     * @return model|null
     */
    public function getPaypalConfig(): ?model
    {
        try {
            return $this->model->first();
        } catch (Throwable $th) {
            return $this->writeLog($th);
        }
    }

    /**
     * Store and return a model newly created
     *
     * @param  Request $request
     * @return Model|null
     */
    public function createOrUpdate($request): ?Model
    {
        try {
            $paypal = $this->model->first();

            if ($paypal) {
                $paypal->update($request);
            } else {
                $paypal = $this->model->create($request);
            }

            return $paypal;
        } catch (Throwable $th) {
            return $this->writeLog($th);
        }
    }
}