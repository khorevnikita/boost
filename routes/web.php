<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get("/check", function () {
    $r = \Illuminate\Support\Facades\DB::table("mobile_users")->get();
    dd($r);
});

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@home');

Route::group(['middleware' => ['admin'], 'prefix' => "admin"], function () {
    Route::get("/", "Admin\HomeController@index");
    Route::resource("games", "Admin\GameController");
    Route::post("games/{id}/banner", "Admin\GameController@banner");
    Route::resource("categories", "Admin\CategoryController");
    Route::resource("products", "Admin\ProductController");
    Route::resource("users", "Admin\UserController");
    Route::resource("options", "Admin\OptionController");
    Route::resource("images", "Admin\ImageController");
});

Route::get("{game_id}", "HomeController@game");
Route::get("{game_id}/{product_id}", "HomeController@product");
