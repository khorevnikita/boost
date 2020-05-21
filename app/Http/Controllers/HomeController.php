<?php

namespace App\Http\Controllers;

use App\Game;
use App\Order;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        #  $this->middleware('auth');
        $games = Game::all();
        View::share("games", $games);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('welcome');
    }

    public function home()
    {
        $user = Auth::user();
        if ($user && $user->role !== "user") {
            return redirect("admin");
        }

        return "home";
    }

    public function game($game_id)
    {
        $game = Game::with("categories")->findOrFail($game_id);
        #$products = Product::whereIn("category_id", $game->categories()->pluck("id"))->get();
        return view("game", compact('game', 'products'));
    }

    public function product($game_id, $product_id)
    {
        $product = Product::findOrFail($product_id);
        if ($product->category->game_id != $game_id) {
            abort(404);
        }
        $order = Order::findTheLast();
        if ($order && $order->products()->find($product->id)) {
            $product->in_order = true;
        }
        return view("product", compact('product'));
    }
}
