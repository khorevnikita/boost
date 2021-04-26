<?php

namespace App\Http\Middleware;

use App\Order;
use App\Rate;
use App\Script;
use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
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
        #dd(1);
        if ($request->ajax()) {
            $currency = $request->currency;
            $rate = $request->rate;

        } else {
            $currency = $request->cookie("currency") ?: "eur";
            $rate = $request->cookie("rate");
        }
        #dd(2);
        if (!$rate) {
            $model = Rate::orderBy("created_at", "desc")->first();
            if (!$model) {
                $exchangeRates = new ExchangeRate();
                $r = $exchangeRates->convert(1, 'EUR', 'USD', Carbon::now());
                $model = new Rate();
                $model->rate = $r;
                $model->save();
            }
            Cookie::queue("rate", $model->rate, 60 * 12);
            $rate = $model->rate;
        }

        Config::set('currency', $currency);
        Config::set('rate', $rate);

        View::share("currency", $currency);
        View::share("rate", $rate);

        $seo = DB::table("seo")->first();
        View::share("seo", $seo);

        $orderItemsCount = 0;
        #$hash = ($_COOKIE);
        #dd($hash);
        $order = Order::findTheLast();
        # dd($order);
        if ($order) {

            $orderItemsCount = $order->products()->count();
        }

        View::share("orderItemsCount", $orderItemsCount);


        $scripts = Script::all();
        View::share("scripts", $scripts);
        return $next($request);
    }
}
