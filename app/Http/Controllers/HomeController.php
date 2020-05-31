<?php

namespace App\Http\Controllers;

use App\Game;
use App\Order;
use App\OrderProduct;
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

    public function game($rewrite)
    {
        $game = Game::with("categories")->where("rewrite", $rewrite)->first();
        if (!$game) {
            abort(404);
        }

        $recentlyViewed = Cache::get("recently_viewed");
        if (!$recentlyViewed) {
            $recentlyViewed = [];
        }
        $recentlyViewed = array_values(array_unique($recentlyViewed));
        $recentlyViewedItems = Product::whereIn("id", array_slice($recentlyViewed, -3))->get();

        return view("game", compact('game', 'products', 'recentlyViewedItems'));
    }

    public function product($game_slug, $product_slug)
    {
        $game = Game::where("rewrite",$game_slug)->first();
        if(!$game){
            abort(404);
        }
        $product = Product::where("rewrite",$product_slug)->whereIn("category_id",$game->categories()->pluck("categories.id"))->first();
        if (!$product) {
            abort(404);
        }
        $order = Order::findTheLast();
        if ($order) {
            $orderProduct = OrderProduct::where("order_id", $order->id)->where("product_id", $product->id)->first();
            if ($orderProduct) {
                $product->in_order = true;
                $product->selected_options = $product->selectedOptions($order);
                $product->pivot = $orderProduct;
            }
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
        #  dd($_COOKIE);
        $crosses = $product->crosses;
        return view("product", compact('product', 'recentlyViewedItems', 'crosses'));
    }
}
