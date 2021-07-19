@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12" style="margin-top: 90px">
                @if(!$order)
                    <div class="alert alert-primary"> You have no active orders</div>
                @else
                    <div class="row" style="min-height: calc(100vh - 250px)">
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
                                {{--}}<div class="col-6 my-2">
                                    <label for="payapp" class="btn btn-primary btn-block btn-type" style="padding: 20px;border-radius: 10px;">
                                        <img src="/images/pay/visa_title.png">
                                        <img src="/images/pay/visa_logo.png">
                                    </label>
                                </div>--}}
                                <div class="col-6 my-2">
                                    <label for="stripe" class="btn btn-primary btn-block btn-type">
                                        <img src="/images/pay/stripe.png">
                                    </label>
                                </div>
                                <div class="col-6 my-2">
                                    <label for="paypal" class="btn btn-outline-secondary btn-block btn-type">
                                        <img src="/images/pay/paypal.png">
                                    </label>
                                </div>
                                {{--<div class="col-6 my-2">
                                  <button class="btn btn-outline-secondary btn-block" style="padding: 20px;border-radius: 10px;">
                                      <img src="/images/pay/amazon.png">
                                  </button>
                              </div>--}}

                            </div>
                            <a role="button" class="text-primary" onclick="$(`#promo-card`).toggleClass('d-none')">Do you have promocode?</a>
                            <div id="promo-card" class="card mt-3 d-none" style="border: 1px solid;">
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
                                    <form id="order-form" action="{{url("/orders/$order->id/form")}}" class="row">
                                        <input id="stripe" class="hide-radio" type="radio" name="operator" value="stripe" checked>
                                        <input id="payapp" class="hide-radio" type="radio" name="operator" value="payapp" >
                                        <input id="paypal" class="hide-radio" type="radio" name="operator" value="paypal" >
                                        <div class="col-12">
                                            <input id="order-email" onchange="localStorage.setItem('email',document.querySelector(`#order-email`).value)" type="text"
                                                   class="form-control" name="email" placeholder=E-mail" value="{{old('email')?($user->email??""):''}}">
                                            <p class="text-danger" data-key="email"></p>
                                            <p class="text-primary">We are going to send details and data for further authorisation to entered email.</p>
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
                                    <button onclick="$('#agree').hasClass('d-none')?$('#agree-err').removeClass('d-none'):$('#order-form').submit()"
                                            class="btn btn-primary btn-block" style="    height: 52px">Pay Now
                                    </button>
                                </div>
                                <div class="col-12 col-sm-6 d-flex mt-2">
                                    <label class="form-check-label">
                                        <span class="checkbox" onclick="$('#agree').toggleClass('d-none');$('#agree-err').addClass('d-none')">
                                            <img src="/images/icons/checkbox.svg" id="agree">
                                        </span>
                                        <a href="{{url("agreement")}}" target="_blank">I agree to Term of Use</a>
                                    </label>
                                </div>
                            </div>
                            <p class="text-danger d-none" id="agree-err">You should agree with the Terms to continue</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem("email")) {
                document.querySelector(`#order-email`).value = localStorage.getItem("email")
            }

            $(".btn-type").click(function () {
                $(".btn-type").removeClass("btn-primary").addClass("btn-outline-secondary");
                $(this).addClass("btn-primary").removeClass("btn-outline-secondary");
            })
        })
    </script>
    @push("scripts")
        <script src="https://js.stripe.com/v3/"></script>
    @endpush
@endsection
