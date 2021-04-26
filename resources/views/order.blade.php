@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12" style="margin-top: 90px">
                @if(!$order)
                    <div class="alert alert-primary"> You have no active orders</div>
                @else
                    <div class="row">
                        <div class="col-12 col-sm-8 offset-sm-2">
                            <h1 class="d-flex justify-content-between">
                                <span style="font-size: inherit">Your payment</span>
                                <span>total:
                                    <span class="text-primary" style="font-size: 22px">
                                        @if ($order->promocode)
                                            {{$order->setPromocode($order->promocode)}}
                                        @else
                                            {{$order->amount}}
                                        @endif
                                        {!! $currency=="usd"?"$":"&euro;" !!}
                                    </span>
                                </span>
                            </h1>
                            <div style="clear:both"></div>
                            <p>Choose payment method:</p>
                            <div class="row">
                                <div class="col-6 my-2">
                                    <button class="btn btn-primary btn-block" style="padding: 20px;border-radius: 10px;">
                                        <img src="/images/pay/visa_title.png">
                                        <img src="/images/pay/visa_logo.png">
                                    </button>
                                </div>
                                {{--<div class="col-6 my-2">
                                    <button class="btn btn-outline-secondary btn-block" style="padding: 20px;border-radius: 10px;">
                                        <img src="/images/pay/stripe.png">
                                    </button>
                                </div>
                                <div class="col-6 my-2">
                                    <button class="btn btn-outline-secondary btn-block" style="padding: 20px;border-radius: 10px;">
                                        <img src="/images/pay/paypal.png">
                                    </button>
                                </div>
                                <div class="col-6 my-2">
                                    <button class="btn btn-outline-secondary btn-block" style="padding: 20px;border-radius: 10px;">
                                        <img src="/images/pay/amazon.png">
                                    </button>
                                </div>--}}

                            </div>
                            <div class="card mt-3" style="    border: 1px solid;">
                                <div class="card-body">
                                    <form method="post" action="{{url("orders/$order->id/promocode")}}" class="row ">
                                        @csrf
                                        <div class="col-6 col-sm-6">
                                            <input type="text" class="form-control" name="promocode" placeholder="Enter promocode"
                                                   value="{{$order->promocode?$order->promocode->code:''}}" style="    height: 52px">


                                            @error('promocode')
                                            <p class="text-danger" data-key="promocode">{{ $message }}</p>
                                            @enderror
                                            @if(session("msg")??null)
                                                <p class="text-danger" data-key="promocode">{{ session("msg") }}</p>
                                            @endif
                                            @if($order->promocode)
                                                <p class="text-success">Promocode activated</p>
                                            @endif
                                        </div>
                                        <div class="col-6 col-sm-6">
                                            <button type="submit" class="btn btn-primary btn-block" style="    height: 52px">Activate</button>
                                        </div>
                                    </form>
                                </div>
                            </div>


                            <div class="card mt-3" style="    border: 1px solid;">
                                <div class="card-body">
                                    <form id="order-form" action="{{url("/orders/$order->id/form")}}" class="row ">
                                        <div class="col-12">
                                            <input type="text" class="form-control" name="email" placeholder=E-mail" value="{{$user->email??""}}">
                                            <p class="text-danger" data-key="email"></p>
                                        </div>
                                        {{-- <div class="col-12 col-sm-6">
                                             <button class="btn btn-primary btn-block" style="    height: 52px">Pay Now</button>
                                         </div>--}}
                                    </form>
                                </div>
                            </div>
                            <div style="clear:both"></div>
                            <div class="row mt-3">
                                <div class="col-12 col-sm-6 mt-2">
                                    <button onclick="$('#order-form').submit()" class="btn btn-primary btn-block" style="    height: 52px">Pay Now</button>
                                </div>
                                <div class="col-12 col-sm-6 d-flex mt-2">
                                    <label class="form-check-label">
                                        <span class="checkbox">
                                            <img src="/images/icons/checkbox.svg">
                                        </span>
                                        <a href="{{url("agreement")}}" target="_blank">I agree to Term of Use</a>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
