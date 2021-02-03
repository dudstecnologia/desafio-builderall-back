<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalHttp\HttpException;

Route::prefix('auth')->group(function () {
    Route::post('login', 'AuthController@login');
    Route::get('logout', 'AuthController@logout');
    Route::get('refresh', 'AuthController@refresh');
    Route::get('user', 'AuthController@user');
});

Route::middleware('auth:api')->group(function () {
    Route::resource('products', 'ProductController');
    Route::resource('paypals', 'PaypalController')->only(['index', 'store']);
});

// Public Routes
Route::get('list-products', 'ProductController@index');
Route::get('paypal-clientid', 'PaypalController@clientId');

Route::post('teste', function (Request $req) {
    $paypal = [
       'client_id' => 'AYslSDWhVKbc6_eZDSvDSEetXNIMhkxTisg8UBvJopst8LLbg25XRCF9JmuPJ_wkM9lVjYUJ7G_W-Uyn',
       'secret' => 'EBwbFyDbbMQelNWVq1m-_t5oNk8GguEoFg39uMZVdbrOwKjjVO4SVYL-EMoG0gW0TyyDIc0Q9pjFM8Bb',
       'settings' => array(
          'mode' => 'sandbox',
          'http.ConnectionTimeOut' => 30,
          'log.LogEnabled' => true,
          'log.FileName' => storage_path() .
          '/logs/paypal.log',
          'log.LogLevel' => 'ERROR'
         ),
    ];

   $environment = new SandboxEnvironment($paypal['client_id'], $paypal['secret']);
   $client = new PayPalHttpClient($environment);

   $request = new OrdersCreateRequest();
    $request->prefer('return=representation');
    $request->body = [
                         "intent" => "CAPTURE",
                         "purchase_units" => [[
                             "reference_id" => "test_ref_id1",
                             "amount" => [
                                 "value" => "25.50",
                                 "currency_code" => "BRL"
                             ]
                         ]],
                         "application_context" => [
                              "cancel_url" => "https://example.com/cancel",
                              "return_url" => "https://example.com/return"
                         ]
                     ];

    try {
        // Call API with your client and get a response for your call
        $response = $client->execute($request);

        // If call returns body in response, you can get the deserialized version from the result attribute of the response
        // print_r($response);
        return response()->json($response->result);
    }catch (HttpException $ex) {
        echo $ex->statusCode;
        print_r($ex->getMessage());
    }
});
