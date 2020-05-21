<?php

namespace App\Http\Controllers;

use App\Game;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::orderBy("is_new");
        $game = Game::findOrFail($request->game_id);
        if ($request->category_id) {
            $products = $products->where("category_id", $request->category_id);
        } else {
            $products = $products->whereIn("category_id", $game->categories()->pluck("id"));
        }
        if ($request->q) {
            $products = $products->where("title", "like", "%$request->q%");
        }

        $products = $products->with("category")->get()->map(function ($p){
            $p->banner = $p->banner;
            return $p;
        });

        return response([
            'status' => "success",
            'products' => $products
        ]);
    }
}
