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

    public function createOrder($total)
    {
        $client = new PayPalHttpClient($this->getEnviroment());

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = $this->getBody($total);

        try {
            // Call API with your client and get a response for your call
            $response = $client->execute($request);

            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            // print_r($response);
            return response()->json($response->result);
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
                //"reference_id" => "test_ref_id1",
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