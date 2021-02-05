<?php

namespace App\Services;

use App\Repositories\PurchaseRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PurchaseService
{
	private $purchaseRepository;
	private $cartService;

    public function __construct(PurchaseRepository $purchaseRepository, CartService $cartService)
    {
        $this->purchaseRepository = $purchaseRepository;
		$this->cartService = $cartService;
    }

	public function store(Request $request)
	{
		try {
			DB::beginTransaction();

			if (!$purchase = $this->purchaseRepository->create(['status' => 0])) {
				return null;
			}

			if (!$total = $this->cartService->store($request, $purchase)) {
				return null;
			}

			if (is_string($total)) return $total;

			dd($total);

			DB::commit();
			return $purchase;
		} catch (Throwable $th) {
			DB::rollBack();
			Log::error($th->getMessage());
		}
	}
}