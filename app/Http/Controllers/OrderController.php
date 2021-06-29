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
use Stripe\Stripe;

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
            'hash' => $order->hash,
            "order_id" => $order->id,
        ]);
    }

    public function findPromocode(Request $request)
    {
        $pc = Promocode::where("code", $request->code)->first();
        if (!$pc) {
            return response()->json([
                'status' => "error",
                "msg" => "Promocode does not exists"
            ]);
        }
        if ($pc->end_at && Carbon::parse($pc->end_at) < Carbon::now()) {
            return response()->json([
                'status' => "error",
                "msg" => "Expired promotional code"
            ]);
        }
        return response()->json([
            'status' => "success",
            "promocode" => $pc,
        ]);
    }

    public function setPromocode($id, Request $request)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            'promocode' => "required|exists:promocodes,code"
        ]);

        $pc = Promocode::where("code", $request->promocode)->first();
        if ($pc->end_at && Carbon::parse($pc->end_at) < Carbon::now()) {
            return back()->withInput()->with("msg", "Expired promotional code");
        }
        $order->promocode_id = $pc->id;
        $order->save();

        return back()->withInput();
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

        if ($request->operator === "stripe") {
            $response = $this->stripe($order);
            return response([
                'status' => "success",
                'sessionId' => $response['session_id'],
                "key" => $response['key']
            ]);
        } else {
            $response = $this->pay($order);
            return response([
                'status' => "success",
                'response' => $response,
            ]);
        }
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
        # var_dump($order->amount);
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

    public function stripe($order)
    {
        $finalPrice = $order->amount;
        if ($order->promocode) {
            $finalPrice = $order->setPromocode($order->promocode);
        }
        $key = config("services.stripe.key");
        Stripe::setApiKey($key);
        header('Content-Type: application/json');
        # $YOUR_DOMAIN = 'http://boost.local';
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($order->currency),
                    'unit_amount' => $finalPrice * 100,
                    'product_data' => [
                        'name' => "Order #$order->id",
                        'order_id' => $order->id,
                        #'images' => ["https://i.imgur.com/EHyR2nP.png"],
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url("/order/success"),
            'cancel_url' => url("/order/decline"),
            'metadata'=>[
                'order_id'=>$order->id,
            ]
        ]);
        return ["session_id" => $checkout_session->id, "key" => config("services.stripe.public")];
    }

    public function pay($order)
    {
        $finalPrice = $order->amount;
        if ($order->promocode) {
            $finalPrice = $order->setPromocode($order->promocode);
        }
        # var_dump($finalPrice);
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
        #Log::info("callback author: " . $request->);
        /*

        {

  "token": "payment token",
  "type": "payment type: payment | payout",
  "status" : "payment status: pending | approved | declined ",
  "extraReturnParam" : "extra params",
  "orderNumber" : "merchant order number",
  "walletToken": "payer's ReactivePay wallet unique identifier, only for reactivepay payments",
  "recurringToken": "payer's previously initialized recurring token, for making recurrent payment repeatedly",
  "sanitizedMask": "payer's sanitized card, if it was provided",
  "amount": "payment amount in cents",
  "currency": "payment currency",
  "gatewayAmount": "exchanged amount in cents",
  "gatewayCurrency": "exchanged currency"
}

         */

        $order = Order::find($request->orderNumber);
        if (!$order) {
            Log::info("ORDER NOT FOUND");
            abort(404);
        }

        /*if ($request->amount !== ($order->amount * 100)) {
            Log::info("WARNIGN! ERROR OR PROMO");
        }*/

        if ($request->status == "declined") {
            Log::info("ORDER DECLINED," . $order->id);
            $order->status = "decline";
            $order->save();
        }
        if ($request->status == "approved") {
            Log::info("ORDER APPROVED," . $order->id);
            $order->status = "payed";
            $order->payed_at = Carbon::now();
            $order->save();
        }
        Log::info("CHECK PASSED,200");
        return response([
            'status' => "success",
        ]);
    }

    public function stripeCallback(Request $request)
    {

        \Stripe\Stripe::setApiKey(config("services.stripe.key"));

        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch (UnexpectedValueException $e) {
            // Invalid payload
            abort(400);
            exit();
        }

// Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                #handlePaymentIntentSucceeded($paymentIntent);
                Log::info(json_encode($paymentIntent));
                break;
            /*case 'payment_method.attached':
                $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
                handlePaymentMethodAttached($paymentMethod);
                break;*/
            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        $example = '{"id":"pi_1J7hPFL5qBX6q7NkjcVm4Fe6","object":"payment_intent","amount":3437,"amount_capturable":0,"amount_received":3437,"application":null,"application_fee_amount":null,"canceled_at":null,"cancellation_reason":null,"capture_method":"automatic","charges":{"object":"list","data":[{"id":"ch_1J7hPYL5qBX6q7NklDBrNH70","object":"charge","amount":3437,"amount_captured":3437,"amount_refunded":0,"application":null,"application_fee":null,"application_fee_amount":null,"balance_transaction":"txn_1J7hPZL5qBX6q7NkL63vIyvg","billing_details":{"address":{"city":null,"country":"RU","line1":null,"line2":null,"postal_code":null,"state":null},"email":"nkhoreff@yandex.ru","name":"asd asd","phone":null},"calculated_statement_descriptor":"BOOSTMYTOON.COM","captured":true,"created":1624974936,"currency":"eur","customer":"cus_JlDr39iLJH68Qc","description":null,"destination":null,"dispute":null,"disputed":false,"failure_code":null,"failure_message":null,"fraud_details":[],"invoice":null,"livemode":false,"metadata":[],"on_behalf_of":null,"order":null,"outcome":{"network_status":"approved_by_network","reason":null,"risk_level":"normal","risk_score":22,"seller_message":"Payment complete.","type":"authorized"},"paid":true,"payment_intent":"pi_1J7hPFL5qBX6q7NkjcVm4Fe6","payment_method":"pm_1J7hPXL5qBX6q7NkVjVRr5LC","payment_method_details":{"card":{"brand":"visa","checks":{"address_line1_check":null,"address_postal_code_check":null,"cvc_check":"pass"},"country":"US","exp_month":12,"exp_year":2022,"fingerprint":"hWmZlq3aymocqhIi","funding":"credit","installments":null,"last4":"4242","network":"visa","three_d_secure":null,"wallet":null},"type":"card"},"receipt_email":null,"receipt_number":null,"receipt_url":"https:\/\/pay.stripe.com\/receipts\/acct_1J3jDyL5qBX6q7Nk\/ch_1J7hPYL5qBX6q7NklDBrNH70\/rcpt_JlDrOnxtENoXVDCXcWGvZwzU0hwpmb5","refunded":false,"refunds":{"object":"list","data":[],"has_more":false,"total_count":0,"url":"\/v1\/charges\/ch_1J7hPYL5qBX6q7NklDBrNH70\/refunds"},"review":null,"shipping":null,"source":null,"source_transfer":null,"statement_descriptor":null,"statement_descriptor_suffix":null,"status":"succeeded","transfer_data":null,"transfer_group":null}],"has_more":false,"total_count":1,"url":"\/v1\/charges?payment_intent=pi_1J7hPFL5qBX6q7NkjcVm4Fe6"},"client_secret":"pi_1J7hPFL5qBX6q7NkjcVm4Fe6_secret_EHt30lZxMG9xBTuoATereSWSM","confirmation_method":"automatic","created":1624974917,"currency":"eur","customer":"cus_JlDr39iLJH68Qc","description":null,"invoice":null,"last_payment_error":null,"livemode":false,"metadata":[],"next_action":null,"on_behalf_of":null,"payment_method":"pm_1J7hPXL5qBX6q7NkVjVRr5LC","payment_method_options":{"card":{"installments":null,"network":null,"request_three_d_secure":"automatic"}},"payment_method_types":["card"],"receipt_email":null,"review":null,"setup_future_usage":null,"shipping":null,"source":null,"statement_descriptor":null,"statement_descriptor_suffix":null,"status":"succeeded","transfer_data":null,"transfer_group":null}';
        #$exampleData = json_decode($example);
        #dd($exampleData);

        http_response_code(200);
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
