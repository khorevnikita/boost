@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Order #{{$order->id}}
                    </div>
                    <div class="card-body">
                        @if($order->user)
                            <h4>User
                                <a href="{{url("admin/users/$user->id/edit")}}">
                                    <svg class="bi bi-pencil" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                              d="M11.293 1.293a1 1 0 0 1 1.414 0l2 2a1 1 0 0 1 0 1.414l-9 9a1 1 0 0 1-.39.242l-3 1a1 1 0 0 1-1.266-1.265l1-3a1 1 0 0 1 .242-.391l9-9zM12 2l2 2-9 9-3 1 1-3 9-9z"/>
                                        <path fill-rule="evenodd"
                                              d="M12.146 6.354l-2.5-2.5.708-.708 2.5 2.5-.707.708zM3 10v.5a.5.5 0 0 0 .5.5H4v.5a.5.5 0 0 0 .5.5H5v.5a.5.5 0 0 0 .5.5H6v-1.5a.5.5 0 0 0-.5-.5H5v-.5a.5.5 0 0 0-.5-.5H3z"/>
                                    </svg>
                                </a>
                            </h4>
                            <ul class="list-unstyled">
                                <li>Name: <strong>{{$user->surname}} {{$user->name}}</strong></li>
                                <li>Email: <strong>{{$user->email}}</strong></li>
                                <li>Phone: <strong>{{$user->phone}}</strong></li>
                                <li>Skype: <strong>{{$user->skype}}</strong></li>
                                <li>Discord: <strong>{{$user->discord}}</strong></li>
                                <li>Bonus: <strong>{{$user->bonus}}</strong></li>
                                <li>Email verification:
                                    @if($user->email_verified_at)
                                        <strong class="text-success">Yes</strong>
                                    @else
                                        <strong class="text-danger">No</strong>
                                    @endif
                                </li>
                            </ul>
                        @endif
                        <h4>Order
                            <a href="{{url("admin/orders/$order->id/edit")}}">
                                <svg class="bi bi-pencil" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                          d="M11.293 1.293a1 1 0 0 1 1.414 0l2 2a1 1 0 0 1 0 1.414l-9 9a1 1 0 0 1-.39.242l-3 1a1 1 0 0 1-1.266-1.265l1-3a1 1 0 0 1 .242-.391l9-9zM12 2l2 2-9 9-3 1 1-3 9-9z"/>
                                    <path fill-rule="evenodd"
                                          d="M12.146 6.354l-2.5-2.5.708-.708 2.5 2.5-.707.708zM3 10v.5a.5.5 0 0 0 .5.5H4v.5a.5.5 0 0 0 .5.5H5v.5a.5.5 0 0 0 .5.5H6v-1.5a.5.5 0 0 0-.5-.5H5v-.5a.5.5 0 0 0-.5-.5H3z"/>
                                </svg>
                            </a>
                        </h4>
                        <ul class="list-unstyled">
                            <li>Created at: <strong>{{$order->created_at}}</strong></li>
                            <li>Payed_at: <strong>{{$order->payed_at}}</strong></li>
                            <li>Comment: <strong>{{$order->comment}}</strong></li>
                            <li>Status: <strong>{{$order->status}}</strong></li>
                            <li>Bonus: <strong>{{$order->bonus()}}</strong></li>
                            @if($order->promocode)
                                <li>Promo code: <strong>{{$order->promocode->title}} ( -{{$order->promocode->value}}{{$order->promocode->currency}} )</strong></li>
                            @endif
                            <li>Payment link: <strong>{{url("/order/$order->id/pay")}}</strong></li>
                        </ul>

                        <h4>Products</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td>#</td>
                                    <td>Product</td>
                                    <td>Options</td>
                                    <td>Calculator</td>
                                    <td>Price</td>
                                </tr>
                                @foreach($order->products as $k=>$product)
                                    <tr>
                                        <td>{{$k+1}}</td>
                                        <td>
                                            <a href="{{url($product->url)}}">{{$product->title}} </a>
                                            <span class="float-right"> {{$product->price}} {{$order->currency}}
                                        </td>
                                        <td>
                                            @if($product->selected_options)
                                                <ol>
                                                    @foreach($product->selected_options as $option)
                                                        <li>
                                                            {{$option->title}}
                                                            <span class="float-right">
                                                                {{$option->price}} {!! $option->type=="abs"?$order->currency:"%" !!}
                                                            </span>
                                                            <div style="clear: both"></div>
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            @endif
                                        </td>

                                        <td>
                                            @if($product->pivot->range)
                                                @php $range = json_decode($product->pivot->range) @endphp
                                                <p class="m-0"><strong>{{$product->calculator->name}}</strong></p>
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
                                                <hr>
                                                <p>
                                                    {{$product->calculator->calc($range)}}
                                                    {!! $order->currency !!}
                                                </p>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            {{$product->final_price}} {!! $order->currency !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <p>
                            <strong>Final price:
                                {{$order->promocode?$order->setPromocode($order->promocode):$order->amount}} {!! $order->currency !!}
                            </strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
