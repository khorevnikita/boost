<?php

namespace App\Http\Controllers;

use App\Game;
use App\Order;
use App\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

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

    public function show()
    {
        $order = Order::findTheLast();
        $commonPrice = 0;
        if($order && $order->products->count() > 0) {
            foreach ($order->products as $product) {
                $commonPrice = $commonPrice + $product->price;
                $orderProduct = $product->orderProducts()->where("order_id", $order->id)->with("options")->first();
                $product->selected_options = $orderProduct->options;
                foreach ($product->selected_options as $option) {
                    if ($option->type == "abs") {
                        $commonPrice = $commonPrice + $option->price;
                    } else {
                        $commonPrice = $product->price * $option->price / 100;
                    }
                }
            }
        }
        return view("order", compact('order', 'commonPrice'));
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
            $order->status = 0;
            $order->save();
            Cache::put('order_hash', $hash);
        }

        # attach product
        $order->products()->detach($request->product_id);
        $order->products()->attach($request->product_id);

        # attach options
        $orderProductItem = OrderProduct::where("order_id", $order->id)->where("product_id", $request->product_id)->first();
        $orderProductItem->options()->detach();
        $orderProductItem->options()->attach($request->options);

        return response([
            'status' => "success",
            'data' => $request->all(),
        ]);
    }

    public function destroy(Request $request)
    {
        $order = Order::findTheLast();
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
        if ($order->products()->count() == 0) {
            $order->delete();
        }

        return response([
            'status' => "success"
        ]);
    }
}
