<?php

namespace App\Services;

use App\Models\Purchase;
use App\Repositories\PurchaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PurchaseService
{
	private $purchaseRepository;
	private $cartService;
	private $paypalService;

    public function __construct(
		PurchaseRepository $purchaseRepository,
		CartService $cartService,
		PaypalService $paypalService)
    {
        $this->purchaseRepository = $purchaseRepository;
		$this->cartService = $cartService;
		$this->paypalService = $paypalService;
    }

	public function store(Request $request)
	{
		try {
			DB::beginTransaction();

			if (!$purchase = $this->purchaseRepository->create(['status' => Purchase::PROCESSANDO])) {
				return null;
			}

			if (!$total = $this->cartService->store($request, $purchase)) {
				return null;
			}

			if (is_string($total)) return $total;

			if (!$order = $this->paypalService->createOrder($total, $purchase)) {
				return 'Error generating a payment order, please try again.';
			}

			DB::commit();

			return $order;
		} catch (Throwable $th) {
			Log::error($th->getMessage());
			DB::rollBack();

			return null;
		}
	}
}