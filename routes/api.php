<?php

use Illuminate\Support\Facades\Route;

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