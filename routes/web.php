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


Route::group(['middleware' => ['admin'], 'prefix' => "admin"], function () {
    Route::get("/", "Admin\HomeController@index");
    Route::resource("games", "Admin\GameController");
    Route::post("games/{id}/banner", "Admin\GameController@banner");
    Route::resource("categories", "Admin\CategoryController");
    Route::resource("products", "Admin\ProductController");
    Route::resource("users", "Admin\UserController");
    Route::resource("options", "Admin\OptionController");
    Route::resource("images", "Admin\ImageController");
    Route::resource("orders", "Admin\OrderController");
    Route::post("orders/{id}", "Admin\OrderController@destroy");
    Route::resource("calculator", "Admin\CalculatorController");
    Route::resource("banners", "Admin\BannerController");

    Route::get("seo", "Admin\SeoController@get");
    Route::post("seo", "Admin\SeoController@save");
});
Route::group(['middleware' => ['currency']], function () {

    Auth::routes();

    Route::get('login/{driver}', 'Auth\LoginController@redirectToProvider');
    Route::get('login/{driver}/callback', 'Auth\LoginController@handleProviderCallback');

    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@home');

    Route::get("order", "OrderController@show");
    Route::get("order/{id}/pay", "OrderController@pay");
    Route::get("order/success", "OrderController@success");
    Route::get("order/decline", "OrderController@decline");
    Route::get("confirm-email/{token}", 'Auth\ConfirmPasswordController@confirm');

    Route::resource("assessments", "AssessmentController");

    Route::get("details", "HomeController@details");
    Route::get("faq", "HomeController@faq");
    Route::get("agreement", "HomeController@agreement");

    Route::get("currency/{code}", function ($code) {
        setcookie("currency_" . config("app.cookie_key"), $code, null, '/');
        return back();
    });

    Route::get("search", "HomeController@search");

    Route::get("{game_slug}", "HomeController@game");
    Route::get("{game_slug}/{product_slug}", "HomeController@product");

});
