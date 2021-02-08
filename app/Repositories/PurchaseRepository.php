<?php

namespace App\Repositories;

use App\Models\Purchase;
use Carbon\Carbon;
use Throwable;

class PurchaseRepository extends BaseRepository
{
	protected $model = Purchase::class;

	public function getPurchaseListByPeriod($request)
	{
		try {
			$start = Carbon::createFromFormat('d/m/Y H:i:s', "$request->start 00:00:00");
			$end = Carbon::createFromFormat('d/m/Y H:i:s', "$request->end 00:00:00")->addDay();

            return $this->model->select(
					'purchases.id',
					'purchases.buyer_email as email',
					'purchases.status',
					'carts.total',
					'purchases.created_at'
				)
				->join('carts', 'carts.purchase_id', 'purchases.id')
				->whereBetween('purchases.created_at', [$start, $end])
				->orderBy('purchases.id', 'desc')
				->get();
        } catch (Throwable $th) {
            return $this->writeLog($th);
        }
	}
}