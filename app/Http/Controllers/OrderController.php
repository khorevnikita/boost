<?php /** @noinspection ALL */

namespace App\Http\Controllers;

use App\Calculator;
use App\Game;
use App\Mail\InfoMail;
use App\Mail\RegisterMail;
use App\Order;
use App\OrderProduct;
use App\Promocode;
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
        if ($order/* && $order->products->count() > 0*/) {
            $order->load(["products", "products.calculator"]);
            foreach ($order->products as $product) {
                $product->selected_options = $product->getSelectedOptions($order);
                if ($product->calculator) {
                    $range = json_decode($product->pivot->range);
                    $calc = $product->calculator;
                    if ($calc->steps->count()) {
                        $from = $calc->steps->where("price", $range->from)->first();
                        if ($from) {
                            $from = $from->title;
                        }
                        $to = $calc->steps->where("price", $range->to)->first();
                        if ($to) {
                            $to = $to->title;
                        }
                    } else {
                        $from = $calc->min_title;
                        $to = $calc->max_title;
                    }
                    $product->calculator->amount = $product->calculator->calc($range);
                    $product->calculator->from = $from;
                    $product->calculator->to = $to;
                }
            }
        }
        return response()->json([
            'status' => "success",
            "order" => $order,
        ]);
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
            setcookie("order_hash", $hash, time() + 3600, '/');
        }

        # attach product
        $order->products()->detach($request->product_id);

        $range = null;
        $calc = Calculator::where("product_id", $request->product_id)->first();
        if ($calc && $request->has("range")) {
            $range = json_encode($request->range);
        }
        $order->products()->attach($request->product_id, ['range' => $range]);

        # attach options
        $orderProductItem = OrderProduct::where("order_id", $order->id)->where("product_id", $request->product_id)->first();
        $orderProductItem->options()->detach();
        $orderProductItem->options()->attach($request->options);

        # calc amount
        $order->amount = $order->commonPrice();
        $order->currency = strtoupper(Config::get("currency") ?: "eur");
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
            # 'contact' => "required|string|max:255",
        ]);

        $order = Order::findOrFail($id);
        if ($request->promocode) {
            $pc = Promocode::where("code", $request->promocode)->first();
            if (!$pc) {
                return response([
                    'status' => "error",
                    'errors' => [
                        "promocode" => ["Promotional code does not exists"]
                    ],

                ], 422);
            }
            if ($pc->end_at && Carbon::parse($pc->end_at) < Carbon::now()) {
                return response([
                    'status' => "error",
                    'errors' => [
                        "promocode" => ["Expired promotional code"]
                    ],

                ], 422);
            }
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
            $user->skype = $request->contact;
            $user->discord = $request->discord;
            $user->save();
            $is_new = true;
            # email here about registration
            Mail::to($user)->send(new RegisterMail($user, $password));

            Auth::user($user, true);
        }

        $order->user_id = $user->id;
        #$order->status = "formed";
        if (isset($pc)) {
            $order->promocode_id = $pc->id;
        }
        $order->save();
        $response = $this->pay($order);
        return response([
            'status' => "success",
            'response' => $response,
        ]);
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'email' => "required|email|max:255",
        ]);
        if ($request->promocode) {
            $pc = Promocode::where("code", $request->promocode)->first();
            if (!$pc) {
                return response([
                    'status' => "error",
                    'errors' => [
                        "promocode" => ["Promotional code does not exists"]
                    ],

                ], 422);
            }
            if ($pc->end_at && Carbon::parse($pc->end_at) < Carbon::now()) {
                return response([
                    'status' => "error",
                    'errors' => [
                        "promocode" => ["Expired promotional code"]
                    ],

                ], 422);
            }
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
            $user->skype = $request->contact;
            $user->discord = $request->discord;
            $user->save();
            $is_new = true;
            # email here about registration
            Mail::to($user)->send(new RegisterMail($user, $password));

            Auth::user($user);
        }
        $order = new Order();
        $order->user_id = $user->id;
        $hash = md5(now());
        $order->hash = $hash;
        $order->status = "formed";
        $order->save();
        $range = null;
        $calc = Calculator::where("product_id", $request->product_id)->first();
        if ($calc && $request->has("range")) {
            $range = json_encode($request->range);
        }
        $order->products()->attach($request->product_id, ['range' => $range]);

        # attach options
        $orderProductItem = OrderProduct::where("order_id", $order->id)->where("product_id", $request->product_id)->first();
        $orderProductItem->options()->detach();
        $orderProductItem->options()->attach($request->options);

        # calc amount
        $order->amount = $order->commonPrice();
        $order->currency = strtoupper(Config::get("currency") ?: "eur");
        $order->save();

        if (isset($pc)) {
            $order->promocode_id = $pc->id;
        }
        $order->save();

        $response = $this->pay($order);
        if (!isset($response->processingUrl)) {
            return response()->json([
                'status' => "error",
                "msg" => $response->errors[0],
            ]);
        }
        return response([
            'status' => "success",
            'response' => $response,
        ]);
    }

    public function checkout($order_id)
    {
        $order = Order::find($order_id);
        if (!$order) {
            return redirect("/");
        }
        return view("order", compact("order"));
    }

    public function directPay($order_id)
    {
        $order = Order::findOrFail($order_id);
        $response = $this->pay($order);
        if (!isset($response->processingUrl)) {
            return response()->json([
                'status' => "error",
                "msg" => $response->errors[0],
            ]);
        }
        return redirect($response->processingUrl);
    }

    public function pay($order)
    {
        $finalPrice = $order->amount;
        if ($order->promocode) {
            $finalPrice = $order->setPromocode($order->promocode);
        }
        $curl = curl_init();
        # "{ \"product\" : \"Your Product\", \"amount\" : "10000", \"currency\" : \"CNY\", \"redirectSuccessUrl\" : \"https://your-site.com/success\", \"redirectFailUrl\" : \"https://your-site.com/fail\", \"extraReturnParam\" : \"your order id or other info\", \"orderNumber\" : \"your order number\", \"locale\" : \"zh\"\n}
        $data = [
            "product" => "Order $order->id",
            "amount" => $finalPrice * 100,
            "currency" => Config::get("currency"),
            "redirectSuccessUrl" => url("/order/success"),
            "redirectFailUrl" => url("/order/decline"),
            "extraReturnParam" => "$order->id",
            "orderNumber" => "$order->id",
            "locale" => "en"
        ];
        $key = config("services.payapp.key");
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://business.payver.eu/api/v1/payments",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                "authorization: Bearer $key",
                "content-type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return json_decode($response);
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
        #$order = Order::where("status", "payed")->where("hash", $hash)->orderBy("id", "desc")->first();
        $order = Order::find($request->orderNumber);
        if ($order) {
            $order->status = "payed";
            $order->payed_at = Carbon::now();
            $order->save();
        }
        return view("order_success", compact('order'));
    }

    public function decline(Request $request)
    {
        $hash = $_COOKIE["order_hash_" . config("app.cookie_key")] ?? "";
        #$order = Order::where("status", "declined")->where("hash", $hash)->orderBy("id", "desc")->first();
        $order = Order::find($request->orderNumber);
        $data = $request->all();
        if ($order) {
            $order->status = "decline";
            $order->save();

        }
        return view("order_decline", compact('order', 'data'));
    }

    public function callback(Request $request)
    {
        Log::info("order callback" . json_encode($request->all()));
        /*#Mail::to("nkhoreff@yandex.ru")->send(new InfoMail(json_encode($request->all())));
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
            #Mail::to("nkhoreff@yandex.ru")->send(new InfoMail("Order declined due to wrong amount"));
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
                Mail::to($order->user->email)->send(new InfoMail("An order of $order->amount EUR has been paid"));

            }
            # notify admin
            #Mail::to("nkhoreff@yandex.ru")->send(new InfoMail("Заказ на сумму $order->amount EUR оплачен."));
        } elseif ($request->payment['status'] == "decline") {
            $order->status = "declined";
            $order->save();
            #Mail::to("nkhoreff@yandex.ru")->send(new InfoMail("Order $order->amount EUR declined by payment system"));
            Mail::to($order->user->email)->send(new InfoMail("Order $order->amount EUR declined by payment system"));
        }
*/
        return response([
            'status' => "success",
        ]);
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
