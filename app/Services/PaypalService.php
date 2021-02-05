<?php

namespace App\Services;

use App\Repositories\PaypalRepository;
use Illuminate\Support\Facades\Log;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalHttp\HttpException;

class PaypalService
{
    private $paypalRepository;

    public function __construct(PaypalRepository $paypalRepository)
    {
        $this->paypalRepository = $paypalRepository;
    }

    private function getEnviroment()
    {
        $paypal = $this->paypalRepository->getPaypalConfig();

        return new SandboxEnvironment($paypal->client_id, $paypal->secret);
    }

    public function createOrder($total, $purchase)
    {
        $client = new PayPalHttpClient($this->getEnviroment());

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = $this->getBody($total);

        try {
            $response = $client->execute($request);

            if ($response->statusCode != 201) return null;

            $purchase->payment()->create(['gateway' => 'paypal', 'transaction_code' => $response->result->id]);

            return $response->result;
        }catch (HttpException $ex) {
            Log::error($ex->getMessage());
            return null;
        }
    }

    private function getBody($total)
    {
        return [
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "value" => $total,
                        "currency_code" => "BRL"
                    ]
                ]
            ],
            "application_context" => [
                "cancel_url" => url('/cancel'),
                "return_url" => url('/return')
            ]
        ];
    }
}