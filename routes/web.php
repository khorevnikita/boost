<?php

use Illuminate\Support\Facades\Cookie;
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

Route::post("auth/login","Auth\LoginController@login");
Route::post("auth/register","Auth\RegisterController@register");

Route::group(['middleware' => ['admin', 'currency'], 'prefix' => "admin"], function () {
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
    Route::resource("promocodes", "Admin\PromocodeController");
    Route::resource("scripts", "Admin\ScriptController");

    Route::get("seo", "Admin\SeoController@get");
    Route::post("seo", "Admin\SeoController@save");

    Route::resource("pages", "Admin\PageController");
});
Route::group(['middleware' => ['currency']], function () {

    Auth::routes();

    Route::get('login/{driver}', 'Auth\LoginController@redirectToProvider');
    Route::get('login/{driver}/callback', 'Auth\LoginController@handleProviderCallback');

    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@home');

    Route::post("orders/{id}/form", "OrderController@form");#->middleware('currency');

    Route::post("purchase", "OrderController@purchase");#->middleware("currency");
    Route::get("order/{id}/cloud-pay", "OrderController@cloudPay");
    Route::get("order/{id}/pay", "OrderController@checkout");
    Route::get("order/success", "OrderController@success");
    Route::get("order/decline", "OrderController@decline");
    Route::get("confirm-email/{token}", 'Auth\ConfirmPasswordController@confirm');

    Route::resource("assessments", "AssessmentController");

    Route::get("details", "HomeController@details");
    Route::get("faq", "HomeController@faq");
    Route::get("agreement", "HomeController@agreement");

    Route::get("currency/{code}", function ($code) {
        Cookie::queue("currency", $code, 60 * 24 * 7 * 365);
        return back();
    });

    Route::get("search", "HomeController@search");

    Route::get("{game_slug}", "HomeController@game");
    Route::get("{game_slug}/{product_slug}", "HomeController@product");

});
