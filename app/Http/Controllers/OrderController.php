<?php

namespace App\Http\Controllers;

use App\Game;
use App\Mail\RegisterMail;
use App\Order;
use App\OrderProduct;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class OrderController extends Controller
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

    public function show(Request $request)
    {

        $order = Order::findTheLast();
        if ($order && $order->products->count() > 0) {
            foreach ($order->products as $product) {
                $product->selected_options = $product->selectedOptions($order);
            }
        }
        $user = Auth::user();
        return view("order", compact('order', 'commonPrice', 'user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $order = Order::findTheLast($user);

        if (!$order) {
            $order = new Order();
            $order->user_id = $user->id ?? null;
            $hash = md5(now());
            $order->hash = $hash;
            $order->status = "new";
            $order->save();
           # dd(config("app.cookie_key"));
            setcookie("order_hash_" . config("app.cookie_key"), $hash, time() + 3600, '/');
        }

        # attach product
        $order->products()->detach($request->product_id);
        $order->products()->attach($request->product_id);

        # attach options
        $orderProductItem = OrderProduct::where("order_id", $order->id)->where("product_id", $request->product_id)->first();
        $orderProductItem->options()->detach();
        $orderProductItem->options()->attach($request->options);

        # calc amount
        $order->amount = $order->commonPrice();
        $order->save();

        return response([
            'status' => "success",
            'data' => $request->all(),
            'hash' => $order->hash
        ]);
    }


    public function form($id, Request $request)
    {
        $request->validate([
            'name' => "required|string|max:255",
            'surname' => "required|string|max:255",
            'phone' => "required|string|max:255",
            'email' => "required|email|max:255",
        ]);

        $order = Order::findOrFail($id);
        if ($order->hash !== $_COOKIE["order_hash_".config("app.cookie_key")]) {
            abort(403);
        }
        $user = User::where("email", $request->email)->first();
        $is_new = false;
        if (!$user) {
            $password = Str::random(8);

            $user = new User();
            $user->name = $request->name;
            $user->surname = $request->surname;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = bcrypt($password);
            $user->confirmation_token = Str::random();
            $user->save();
            $is_new = true;
            # email here about registration
            Mail::to($user)->send(new RegisterMail($user, $password));
        }

        $order->user_id = $user->id;
        $order->status = "formed";
        $order->save();

        $user->bonus = ($user->bonus ?: 0) + $order->bonus();
        $user->save();
        return response([
            'status' => "success",
            'data' => [
                'is_new' => $is_new,
            ]
        ]);
    }

    public function destroy(Request $request)
    {
        $order = Order::findTheLast($request->order_hash);
        if (!$order) {
            return response([
                'status' => "error",
                'msg' => "Order not found"
            ]);
        }

        if ($request->type == "product") {
            $order->products()->detach($request->product);
        } elseif ($request->type == "option") {
            $orderProduct = OrderProduct::where("order_id", $order->id)->where("product_id", $request->product)->first();
            if ($orderProduct) {
                $orderProduct->options()->detach($request->option);
            }
        }

        $order->amount = $order->commonPrice();
        $order->save();
        if ($order->products()->count() == 0) {
            $order->delete();
        }

        return response([
            'status' => "success"
        ]);
    }
}
