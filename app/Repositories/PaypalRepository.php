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
     * @return Model|null
     */
    public function getPaypalConfig(): ?Model
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

    /**
     * Return paypal client_id
     *
     * @return string|null
     */
    public function getClientId(): ?string
    {
        try {
            $paypal = $this->model->first();

            return $paypal->client_id;
        } catch (Throwable $th) {
            return $this->writeLog($th);
        }
    }
}
