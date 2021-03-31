@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12" style="margin-top: 90px">
                @if(!$order)
                    <div class="alert alert-primary"> You have no active orders</div>
                @else
                    <h4>Order #{{$order->id}} ({{$order->created_at}})</h4>
                    <div class="row">
                        <div class="col-6">
                            <div class="list-group" id="list-tab" role="tablist">
                                @foreach($order->products as $k=>$product)
                                    <a class="list-group-item list-group-item-action order-group-item @if(!$k) active @endif" id="list-home-list_{{$k}}" data-toggle="list" href="#list-{{$k}}" role="tab"
                                       aria-controls="home">
                                        {{$product->title}}
                                        <span class="float-right">
                                            {{$product->price}} {!! $currency=="usd"?"%":"&euro;" !!}
                                            <button data-type="product" data-product="{{$product->id}}" type="button" class="btn btn-link text-white js-remove-item-from-order">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g id="clear_24px">
                                                        <path id="icon/content/clear_24px"
                                                              d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41Z"
                                                              fill="currentColor" fill-opacity="1"/>
                                                    </g>
                                                </svg>
                                            </button>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="tab-content" id="nav-tabContent">
                                @foreach($order->products as $key=>$product)
                                    <div class="tab-pane fade show  @if(!$key) active @endif" id="list-{{$key}}" role="tabpanel" aria-labelledby="list-home-list_{{$key}}">
                                        @if(count($product->getSelectedOptions($order)))
                                            <h4>Options:</h4>
                                            <ol>
                                                @foreach($product->getSelectedOptions($order) as $option)
                                                    <li>
                                                        {{$option->title}}
                                                        <span class="float-right">{{$option->price}}
                                                            @if($option->type=="abs")
                                                                {!! $currency=="usd"?"%":"&euro;" !!}
                                                            @else
                                                                %
                                                            @endif
                                                        <button data-type="option" data-product="{{$product->id}}" data-option="{{$option->id}}" type="button"
                                                                class="btn btn-link text-white js-remove-item-from-order">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<g id="clear_24px">
<path id="icon/content/clear_24px" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41Z" fill="currentColor"
      fill-opacity="1"/>
</g>
</svg>
                                                        </button>
                                                    </span>
                                                        <div style="clear: both"></div>
                                                    </li>
                                                @endforeach
                                            </ol>
                                        @endif
                                        @if($product->pivot->range)
                                            @php $range = json_decode($product->pivot->range) @endphp
                                            @if($range)
                                                <h4>{{$product->calculator->name}}</h4>
                                                <p>
                                                    @if($product->calculator->steps->count()>0)
                                                        @php
                                                            $from = $product->calculator->steps->where("price",$range->from)->first();
                                                            $to = $product->calculator->steps->where("price",$range->to)->first();
                                                        @endphp
                                                        @if($from && $to)
                                                            {{$from->title}} - {{$to->title}}
                                                        @endif
                                                    @else
                                                        {{$product->calculator->min_title}}:
                                                        <strong>{{$range->from}}</strong>
                                                        -
                                                        {{$product->calculator->max_title}}:
                                                        <strong>{{$range->to}}</strong>
                                                    @endif
                                                    :
                                                    <strong class="text-primary">
                                                        {{$product->calculator->calc($range,true)}} {!! $currency=="usd"?"%":"&euro;" !!}
                                                    </strong>
                                                </p>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <h4 class="mt-3">Contact form</h4>
                    <form id="order-form" action="{{url("/api/orders/$order->id/form")}}">
                        {{--<div class="row">
                            <div class="col form-group">
                                <input class=" form-control" name="surname" placeholder="Surname" value="{{$user->surname??""}}">
                                <p class="text-danger" data-key="surname"></p>
                            </div>
                            <div class="col form-group">
                                <input class=" form-control" name="name" placeholder="Name" value="{{$user->name??""}}">
                                <p class="text-danger" data-key="name"></p>
                            </div>
                        </div>--}}
                        <div class="row">
                            <div class="col form-group">
                                <input class=" form-control" name="email" placeholder="E-mail" value="{{$user->email??""}}">
                                <p class="text-danger" data-key="email"></p>
                            </div>
                            {{--<div class="col form-group">
                                <input class=" form-control" name="phone" placeholder="Phone" value="{{$user->phone??""}}">
                                <p class="text-danger" data-key="phone"></p>
                            </div>--}}
                            <div class="col form-group">
                                <input class=" form-control" name="contact" placeholder="Skype or discord" value="{{$user->skype??""}}">
                                <p class="text-danger" data-key="contact"></p>
                            </div>
                        </div>

                        {{--<div class="row">
                            <div class="col form-group">
                                <input class=" form-control" name="skype" placeholder="Skype" value="{{$user->skype??""}}">
                            </div>
                            <div class="col form-group">
                                <input class=" form-control" name="discord" placeholder="Discord" value="{{$user->discord??""}}">
                            </div>
                        </div>--}}

                        <button type="submit" class="btn btn-primary float-right mt-3">Pay</button>
                        <h4 class="mt-3">Common price: <span class="text-primary">{{$order->amount}} {!! $currency=="usd"?"%":"&euro;" !!}
                            </span>
                        </h4>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
