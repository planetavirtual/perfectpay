<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => '/v1', 'namespace' => 'Api\V1', 'as' => 'api.'], function () {
    Route::get('sales/report', 'SalesController@report');
    Route::resource('sales', 'SalesController', ['except' => ['create', 'edit']]);
    Route::resource('products', 'ProductsController', ['except' => ['create', 'edit']]);
    Route::resource('customers', 'CustomersController', ['except' => ['create', 'edit']]);
});