<?php

namespace App\Http\Controllers;

use App\Game;
use App\Product;
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

        $products = $products->with("category")->get()->map(function ($p) {
            $p->banner = $p->banner;
            $p->url = $p->url;
            return $p;
        });


        return response([
            'status' => "success",
            'products' => $products
        ]);
    }
}
