<?php

namespace App\Http\Middleware;

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
        if (isset($_COOKIE["currency_" . config("app.cookie_key")]) && $_COOKIE["currency_" . config("app.cookie_key")] == "usd") {
            $currency = "usd";
        }
        Config::set('currency', $currency);

        View::share("currency", $currency);

        $seo = DB::table("seo")->first();
        View::share("seo", $seo);

        return $next($request);
    }
}
