<?php

namespace App\Http\Middleware;

use App\Order;
use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class CurrencyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $currency = "eur";
        $rate = 1;
        if (isset($_COOKIE["currency_" . config("app.cookie_key")]) && $_COOKIE["currency_" . config("app.cookie_key")] == "usd") {
            $currency = "usd";
            $exchangeRates = new ExchangeRate();
            $rate = $exchangeRates->convert(1, 'EUR', 'USD', Carbon::now());
        }

        Config::set('currency', $currency);
        Config::set('rate', $rate);

        View::share("currency", $currency);
        View::share("rate", $rate);

        $seo = DB::table("seo")->first();
        View::share("seo", $seo);

        $orderItemsCount = 0;
        $order = Order::findTheLast();
        if ($order) {
            $orderItemsCount = $order->products()->count();
        }

        View::share("orderItemsCount", $orderItemsCount);
        return $next($request);
    }
}
