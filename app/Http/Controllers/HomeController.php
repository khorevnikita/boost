<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Game;
use App\Order;
use App\OrderProduct;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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

        $banner = Banner::whereNull("game_id")->where("published", 1)->first();
        return view('welcome', compact('banner'));
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
        $game = Game::where("rewrite", $game_slug)->first();
        if (!$game) {
            abort(404);
        }
        $product = Product::where("rewrite", $product_slug)->whereIn("category_id", $game->categories()->pluck("categories.id"))->first();
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
        $crosses = $product->crosses;

        $calculator = $product->calculator()->with('steps')->first();
        if ($calculator && $calculator->steps->count() > 0) {
            $steps = $calculator->steps->sortBy("price");
            $calculator->min_value = $steps->first()->title;
            $calculator->max_value = $steps->last()->title;
        }
        //dd($calculator->toArray());
        return view("product", compact('product', 'recentlyViewedItems', 'crosses', 'calculator'));
    }

    public function details()
    {
        return view("details");
    }

    public function faq()
    {
        return view("faq");
    }

    public function agreement()
    {
        return view("agreement");
    }

    public function search(Request $request)
    {
        $products = Product::where("title", "like", "%$request->q%");
        $products = $products
            ->leftJoin("assessments", "assessments.product_id", "=", "products.id")
            ->select('products.*', DB::raw('AVG(assessments.value) as ratings_average'))
            ->groupBy("products.id")
            ->orderBy("ratings_average", "desc");

        $products = $products->with("category")->paginate(10);

        return view("search", compact('products'));
    }
}
