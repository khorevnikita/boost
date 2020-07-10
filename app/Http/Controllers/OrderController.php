<?php

namespace App\Http\Controllers;

use App\Calculator;
use App\Game;
use App\Mail\InfoMail;
use App\Mail\RegisterMail;
use App\Order;
use App\OrderProduct;
use App\User;
use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;
use Carbon\Carbon;
use ecommpay\Gate;
use ecommpay\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
                $product->selected_options = $product->selectedOptions($order);;
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

        $range = null;
        $calc = Calculator::where("product_id", $request->product_id)->first();
        if ($calc && $request->has("range")) {
            $range = json_encode($request->range);
        }
        #dd($range);
        $order->products()->attach($request->product_id, ['range' => $range]);

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
            #  'name' => "required|string|max:255",
            #  'surname' => "required|string|max:255",
            #  'phone' => "required|string|max:255",
            'email' => "required|email|max:255",
            'contact' => "required|string|max:255",
        ]);

        $order = Order::findOrFail($id);
        /*if ($order->hash !== $_COOKIE["order_hash_" . config("app.cookie_key")]) {
           // abort(403);
        }*/
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
            $user->skype = $request->contact;
            $user->discord = $request->discord;
            $user->save();
            $is_new = true;
            # email here about registration
            Mail::to($user)->send(new RegisterMail($user, $password));
        }

        $order->user_id = $user->id;
        if ($request->type == "bitcoin") {
            $order->status = "formed";
        }
        # $order->status = "formed";
        $order->save();

        $currency = "eur";
        if (isset($_COOKIE["currency_" . config("app.cookie_key")]) && $_COOKIE["currency_" . config("app.cookie_key")] == "usd") {
            $currency = "usd";
        }
        $exchangeRates = new ExchangeRate();
        $price = $exchangeRates->convert($order->amount, strtoupper($currency), 'RUB', Carbon::now());
        $order->amount = $price;
        $order->user = $user;

        if ($request->type == "default") {

            $payment = new Payment(config("services.ecommpay.id"));
            // Идентификатор проекта

            $payment->setPaymentAmount($price)->setPaymentCurrency(strtoupper(Config::get("currency")));
            // Сумма (в минорных единицах валюты) и валюта (в формате ISO-4217 alpha-3)

            $payment->setPaymentId($order->id);
            // Идентификатор платежа, уникальный в рамках проекта

            $payment->setPaymentDescription("Тест");
            // Описание платежа. Не обязательный, но полезный параметр

            $gate = new Gate(config("services.ecommpay.secret"));
            // Секретный ключ проекта, полученный от ECommPay при интеграции

            $url = $gate->getPurchasePaymentPageUrl($payment);
        }


        return response([
            'status' => "success",
            'data' => [
                'is_new' => $is_new,
                'url' => $url??null,
                'order' => $order,
                'type' => $request->type,
                'bitcoin' => $request->type == "bitcoin" ? config("services.bitcoin.hash") : null
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

    public function success(Request $request)
    {
        $hash = $_COOKIE["order_hash_" . config("app.cookie_key")] ?? "";
        $order = Order::where("status", "payed")->where("hash", $hash)->orderBy("id", "desc")->first();
        return view("order_success", compact('order'));
    }

    public function decline(Request $request)
    {
        $hash = $_COOKIE["order_hash_" . config("app.cookie_key")] ?? "";
        $order = Order::where("status", "declined")->where("hash", $hash)->orderBy("id", "desc")->first();
        return view("order_decline", compact('order'));
    }

    public function callback(Request $request)
    {
        if ($request->project_id != config("services.ecommpay.id")) {
            Mail::to("nkhoreff@yandex.ru")->send(new InfoMail("Callback api key is wrong"));

            return response([
                'status' => "error",
                'code' => "403",
                'msg' => "app_id is wrong"
            ]);
        }

        $order = Order::find($request->payment['id']);
        if (!$order) {
            Mail::to("nkhoreff@yandex.ru")->send(new InfoMail("Order not found: " . json_encode($request->all())));
            return response([
                'status' => "error",
                'code' => "404",
                'msg' => "order not found"
            ]);
        }

        if ($order->amount * 100 != $request->payment['sum']['amount']) {
            $order->status = "declined";
            $order->save();
            Mail::to("nkhoreff@yandex.ru")->send(new InfoMail("Order declined due to wrong amount"));
            Mail::to($order->user->email)->send(new InfoMail("Order declined due to wrong amount"));
            return response([
                'status' => "error",
                'code' => "500",
                'msg' => "amount is wrong"
            ]);
        }
        if ($request->payment['status'] == "success") {
            $order->status = "payed";
            $order->save();

            # notify user
            if ($order->user) {
                Mail::to($order->user->email)->send(new InfoMail("Заказ на сумму $order->amount EUR оплачен."));

            }
            # notify admin
            Mail::to("nkhoreff@yandex.ru")->send(new InfoMail("Заказ на сумму $order->amount EUR оплачен."));
        } else {
            $order->status = "declined";
            $order->save();
            Mail::to("nkhoreff@yandex.ru")->send(new InfoMail("Order $order->amount EUR declined by payment system"));
            Mail::to($order->user->email)->send(new InfoMail("Order $order->amount EUR declined by payment system"));
        }

        return response([
            'status' => "success",
        ]);
    }

    public function pay($order_id)
    {
        $order = Order::findOrFail($order_id);
        /* Заявка на оплату */
        $payment = new Payment(config("services.ecommpay.id"));
        // Идентификатор проекта

        $payment->setPaymentAmount($order->amount * 100)->setPaymentCurrency('EUR');
        // Сумма (в минорных единицах валюты) и валюта (в формате ISO-4217 alpha-3)

        $payment->setPaymentId($order->id);
        // Идентификатор платежа, уникальный в рамках проекта

        $payment->setPaymentDescription("Тест");
        // Описание платежа. Не обязательный, но полезный параметр

        $gate = new Gate(config("services.ecommpay.secret"));
        // Секретный ключ проекта, полученный от ECommPay при интеграции

        /* Запрос для вызова платёжной формы */
        $url = $gate->getPurchasePaymentPageUrl($payment);

        return redirect($url);
    }

    public function cloudPay($id)
    {
        $order = Order::findOrFail($id);
        $exchangeR = new ExchangeRate();
        $price = $exchangeR->convert($order->amount, "EUR", 'RUB', Carbon::now());
        $order->amount = $price;
        return view("order_pay", compact('order'));
    }

    public function payed($id, Request $request)
    {
        $order = Order::find($id);
        if (!$order) {
            abort(404);
        }
        if ($order->user->email != $request->email) {
            abort(403);
        }

        $order->status = "payed";
        $order->save();

        return response([
            'status' => "success"
        ]);
    }
}
