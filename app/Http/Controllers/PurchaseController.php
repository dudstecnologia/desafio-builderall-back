<?php

namespace App\Http\Controllers;

use App\Repositories\PurchaseRepository;
use App\Services\PaypalService;
use App\Services\PurchaseService;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    private $purchaseService;
    private $paypalService;
    private $purchaseRepository;

    public function __construct(
        PurchaseService $purchaseService,
        PaypalService $paypalService,
        PurchaseRepository $purchaseRepository)
    {
        $this->purchaseService = $purchaseService;
        $this->paypalService = $paypalService;
        $this->purchaseRepository = $purchaseRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $purchase = $this->purchaseService->store($request);

        if (!$purchase || is_string($purchase)) {
            return response()->json(['error' => is_string($purchase) ? $purchase : 'Unable to complete purchase'], 400);
        }

        return response()->json($purchase);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function captureOrder($id)
    {
        $capture = $this->paypalService->captureOrder($id);

        return response()->json($capture);
    }

    public function purchaseListByPeriod(Request $request)
    {
        if (!$purchase = $this->purchaseRepository->getPurchaseListByPeriod($request)) {
            return response()->json([], 400);
        }

        return response()->json(compact('purchase'));
    }
}
