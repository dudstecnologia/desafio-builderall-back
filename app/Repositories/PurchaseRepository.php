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
			$start = Carbon::parse($request->start);
			$end = Carbon::parse($request->end)->addDay();

            return $this->model->whereBetween('created_at', [$start, $end])
				->orderBy('id', 'desc')
				->get();
        } catch (Throwable $th) {
            return $this->writeLog($th);
        }
	}
}