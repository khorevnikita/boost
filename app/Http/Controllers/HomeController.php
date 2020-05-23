<?php

namespace App\Http\Controllers;

use App\Game;
use App\Order;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
        if (!$user) {
            return redirect(url("login"));
        }
        if ($user->role !== "user") {
            return redirect("admin");
        }

        $orders = $user->orders->where("status", "!=", "new")->sortByDesc("id");
        foreach ($orders as $order) {
            foreach ($order->products as $product) {
                $product->selected_options = $product->selectedOptions($order);
            }
        }
        return view("home", compact('user', 'orders'));
    }

    public function game($game_id)
    {
        $game = Game::with("categories")->findOrFail($game_id);
        #$products = Product::whereIn("category_id", $game->categories()->pluck("id"))->get();
        $recentlyViewed = Cache::get("recently_viewed");
        if (!$recentlyViewed) {
            $recentlyViewed = [];
        }
        $recentlyViewed = array_values(array_unique($recentlyViewed));
        $recentlyViewedItems = Product::whereIn("id", array_slice($recentlyViewed, -3))->get();

        return view("game", compact('game', 'products', 'recentlyViewedItems'));
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

        $recentlyViewed = Cache::get("recently_viewed");
        if (!$recentlyViewed) {
            $recentlyViewed = [];
        }

        $recentlyViewed[] = $product->id;
        $recentlyViewed = array_values(array_unique($recentlyViewed));
        Cache::forget("recently_viewed");
        Cache::put("recently_viewed", $recentlyViewed, 60 * 60);
        $recentlyViewedItems = Product::whereIn("id", array_slice($recentlyViewed, -3))->get();

      #  $product->rating = $product->assessments->avg("value");

        $crosses = $product->crosses;
        return view("product", compact('product', 'recentlyViewedItems','crosses'));
    }
}
