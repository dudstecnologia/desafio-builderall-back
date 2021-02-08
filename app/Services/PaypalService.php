<?php

namespace App\Services;

use App\Models\Purchase;
use App\Repositories\PaymentRepository;
use App\Repositories\PaypalRepository;
use Illuminate\Support\Facades\Log;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalHttp\HttpException;

class PaypalService
{
    private $paypalRepository;
    private $paymentRepository;

    public function __construct(PaypalRepository $paypalRepository, PaymentRepository $paymentRepository)
    {
        $this->paypalRepository = $paypalRepository;
        $this->paymentRepository = $paymentRepository;
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
        $request->prefer('return=minimal'); // minimal || representation
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

    public function captureOrder($id)
    {
        $client = new PayPalHttpClient($this->getEnviroment());

        $request = new OrdersCaptureRequest($id);
        $request->prefer('return=minimal');

        try {
            $response = $client->execute($request);

            if ($response->statusCode != 201) return null;

            if ($response->result->status == "COMPLETED") {
                $this->updatePurchaseStatus($id, $response->result);
            }

            return $response->result;
        }catch (HttpException $ex) {
            Log::error($ex->getMessage());
            return null;
        }
    }

    private function updatePurchaseStatus($id, $result)
    {
        $payment = $this->paymentRepository->getPaymentByTransactionCode($id);

        $payment->purchase->update([
            "buyer_email" => $result->payer->email_address,
            "status" => Purchase::CONCLUIDO
        ]);
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