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

Route::post("orders", "OrderController@store");
Route::put("orders", "OrderController@destroy");
Route::post("orders/{id}/form", "OrderController@form")->middleware('currency');
Route::post("orders/{id}/payed", "OrderController@payed");#->middleware('currency');
Route::get("products", "ProductController@index");
Route::post("products/{id}/vote", "ProductController@vote");
Route::get("options","Admin\OptionController@indexJson");
Route::post("order/callback","OrderController@callback");
