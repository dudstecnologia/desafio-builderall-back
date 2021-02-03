<?php

namespace App\Http\Controllers;

use App\Repositories\PaypalRepository;
use Illuminate\Http\Request;

class PaypalController extends Controller
{
    private $paypalRepository;

    public function __construct(PaypalRepository $paypalRepository)
    {
        $this->paypalRepository = $paypalRepository;
    }

    /**
     * Show paypal data.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!$paypal = $this->paypalRepository->getPaypalConfig()) {
            return response()->json(['error' => 'Could not paypal data'], 400);
        }

        return response()->json(compact('paypal'));
    }

    /**
     * Store a newly created paypal in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$paypal = $this->paypalRepository->createOrUpdate($request->all())) {
            return response()->json(['error' => 'Couldn\'t save Paypal'], 400);
        }

        return response()->json(compact('paypal'));
    }

    /**
     * Show the paypal client_id
     *
     * @return \Illuminate\Http\Response
     */
    public function clientId()
    {
        if (!$client_id = $this->paypalRepository->getClientId()) {
            return response()->json(['error' => 'Could not get paypal credentials'], 400);
        }

        return response()->json(compact('client_id'));
    }
}
