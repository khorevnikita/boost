<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\RegisterMail;
use App\Order;
use App\OrderProduct;
use App\User;
use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;
use Carbon\Carbon;
use ecommpay\Gate;
use ecommpay\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = Order::orderBy("id", "desc");

        if ($request->status) {
            $orders = $orders->where("status", $request->status);
        }

        if ($request->user) {
            $search = $request->user;
            $orders = $orders->whereHas("user", function ($q) use ($search) {
                $q->where("surname", "like", "%$search%")
                    ->orWhere("name", "like", "%$search%")
                    ->orWhere("email", "like", "%$search")
                    ->orWhere("phone", "like", "%$search%");
            });
        }

        $orders = $orders->paginate(10);

        return view("admin.orders.index", compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Illuminate\Support\Facades\Gate::denies('update-orders')) {
            abort(403);
        }

        return view("admin.orders.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Illuminate\Support\Facades\Gate::denies('update-orders')) {
            abort(403);
        }

        $request->validate([
            "email" => "required|email",
            "amount" => "required|numeric",
            'contact'=>"required"
        ]);
        $user = User::where("email", $request->email)->first();
        if (!$user) {
            $password = Str::random(8);

            $user = new User();
            $user->name = $request->name;
            $user->surname = $request->surname;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = bcrypt($password);
            $user->confirmation_token = Str::random();
            $user->skype = $request->contact;
            $user->discord = $request->discord;
            $user->save();
            $is_new = true;
            # email here about registration
            Mail::to($user)->send(new RegisterMail($user, $password));
        }

        $amount = $request->amount;
        $currency = strtoupper($request->currency);
        if ($currency !== "EUR") {
            $exchangeRates = new ExchangeRate();
            $amount = $exchangeRates->convert($amount, $currency, 'EUR', Carbon::now());
        }

        $order = new Order();
        $order->amount = round($amount, 2);
        $order->status = "new";
        $order->hash = md5(now());
        $order->user_id = $user->id;
        $order->comment = $request->comment;
        $order->save();


        /*$payment = new Payment(config("services.ecommpay.id"));
        // Идентификатор проекта

        $payment->setPaymentAmount($request->amount * 100)->setPaymentCurrency($currency);
        // Сумма (в минорных единицах валюты) и валюта (в формате ISO-4217 alpha-3)

        $payment->setPaymentId($order->id);
        // Идентификатор платежа, уникальный в рамках проекта

        $payment->setPaymentDescription("Тест");
        // Описание платежа. Не обязательный, но полезный параметр

        $gate = new Gate(config("services.ecommpay.secret"));
        // Секретный ключ проекта, полученный от ECommPay при интеграции

        $url = $gate->getPurchasePaymentPageUrl($payment);*/

        return response()->json([
            'status' => "success",
            'url' => url("order/$order->id/pay"),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        if (\Illuminate\Support\Facades\Gate::denies('update-orders')) {
            abort(403);
        }
        if ($order->products->count() > 0) {
            foreach ($order->products as $product) {
                $product->selected_options = $product->selectedOptions($order);
                $product->final_price = $product->price;
                if ($product->selected_options) {
                    foreach ($product->selected_options as $option) {
                        if ($option->type == "abs") {
                            $product->final_price = $product->final_price + $option->price;
                        } else {
                            $product->final_price = $product->final_price + $product->price * $option->price / 100;
                        }
                    }
                }
                if ($product->pivot->range) {
                    $product->final_price = $product->final_price + $product->calculator->calc(json_decode($product->pivot->range));
                }
            }
        }
        $user = $order->user;
        return view("admin.orders.show", compact('order', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        if (\Illuminate\Support\Facades\Gate::denies('update-orders')) {
            abort(403);
        }
        if ($order->products->count() > 0) {
            foreach ($order->products as $product) {
                $product->selected_options = $product->selectedOptions($order);
            }
        }
        return view("admin.orders.edit", compact('order'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        if (\Illuminate\Support\Facades\Gate::denies('update-orders')) {
            abort(403);
        }
        $order->status = $request->status;
        $order->save();

        return back();
    }

    /**
     * @param Order $order
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($order_id, Request $request)
    {
        if (\Illuminate\Support\Facades\Gate::denies('update-orders')) {
            abort(403);
        }
        $order = Order::find($order_id);
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
