<?php

namespace App\Http\Controllers;

use App\Game;
use App\Product;
use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where("products.id", ">", 0);
        $game = Game::findOrFail($request->game_id);
        if ($request->category_id) {
            $products = $products->where("category_id", $request->category_id);
        } else {
            $products = $products->whereIn("category_id", $game->categories()->pluck("id"));
        }
        if ($request->q) {
            $products = $products->where("title", "like", "%$request->q%");
        }
        $take = 30;
        $skip = ($request->page - 1) * $take;
        $pagesCount = ceil($products->count() / $take);
        switch ($request->sort_by) {
            case"price":
                $products = $products->orderBy("price", "asc");
                break;
            case "popularity":
                $products = $products
                    ->leftJoin("assessments", "assessments.product_id", "=", "products.id")
                    ->select('products.*', DB::raw('AVG(assessments.value) as ratings_average'))
                    ->groupBy("products.id")
                    ->orderBy("ratings_average", "desc");
                #   $products = $products->orderBy("average_rating","desc");
                break;
            default:
        }

        $products = $products->with("category");

        $rate = 1;
        if ($request->currency == "usd") {
            $exchangeRates = new ExchangeRate();
            $rate = $exchangeRates->convert(1, 'EUR', 'USD', Carbon::now());
        }

        $products = $products->skip($skip)->take($take)->get()->map(function ($p) use ($rate) {
            $p->banner = $p->banner;
            $p->url = $p->url;
            $p->price = $p->price * $rate;
            return $p;
        });


        return response([
            'status' => "success",
            'products' => $products,
            'pages_count' => $pagesCount
        ]);
    }
}
