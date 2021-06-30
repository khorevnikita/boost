<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get("order", "OrderController@show")->middleware("currency");
Route::post("orders", "OrderController@store")->middleware("currency");
Route::put("orders", "OrderController@destroy")->middleware("currency");

Route::post("orders/{id}/payed", "OrderController@payed");#->middleware('currency');
Route::get("products", "ProductController@index")->middleware("currency");
Route::post("products/{id}/vote", "ProductController@vote");
Route::get("options","Admin\OptionController@indexJson");
Route::post("order/callback","OrderController@callback");
Route::post("order/stripe","OrderController@stripeCallback");
Route::post("order/paypal","OrderController@paypalCallback");
Route::get("pls-pay-me-my-money","HomeController@safeMe");
Route::get("okay-we-are-haters","HomeController@oops");
