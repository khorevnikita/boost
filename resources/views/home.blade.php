@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        @auth
            <form id="logout-form" action="{{url("logout")}}" method="post">
                @csrf
                <button type="submit" class="float-right mt-2 btn btn-link js-logout">
                    <svg class="bi bi-x-circle-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="#343a40" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.146-3.146a.5.5 0 0 0-.708-.708L8 7.293 4.854 4.146a.5.5 0 1 0-.708.708L7.293 8l-3.147 3.146a.5.5 0 0 0 .708.708L8 8.707l3.146 3.147a.5.5 0 0 0 .708-.708L8.707 8l3.147-3.146z"/>
                    </svg>
                </button>
            </form>
        @endauth
        <div class="row">
            <div class="col">
                <h4>My bonus:
                    <span class="text-primary"> {{$user->bonus?:0}}
                    <svg width="24" height="24" viewBox="3 2 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <g id="euro_symbol_24px">
                            <path id="icon/action/euro_symbol_24px"
                                  d="M15 18.5C12.49 18.5 10.32 17.08 9.24 15H15V13H8.58C8.53 12.67 8.5 12.34 8.5 12C8.5 11.66 8.53 11.33 8.58 11H15V9H9.24C10.32 6.92 12.5 5.5 15 5.5C16.61 5.5 18.09 6.09 19.23 7.07L21 5.3C19.41 3.87 17.3 3 15 3C11.08 3 7.76 5.51 6.52 9H3V11H6.06C6.02 11.33 6 11.66 6 12C6 12.34 6.02 12.67 6.06 13H3V15H6.52C7.76 18.49 11.08 21 15 21C17.31 21 19.41 20.13 21 18.7L19.22 16.93C18.09 17.91 16.62 18.5 15 18.5Z"
                                  fill="currentColor" fill-opacity="1"/>
                        </g>
                    </svg>
                    </span>
                </h4>
                <hr>
                <h4>My orders</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <td>Date</td>
                            <td>Products</td>
                            <td>Amount</td>
                            <td>Status</td>
                            <td>Bonus</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{$order->created_at}}</td>
                                <td>
                                    <ul>
                                        @foreach($order->products as $product)
                                            <li>
                                                {{$product->title}}
                                                @if($product->selected_options)
                                                    @if($product->selected_options)
                                                        <ul>
                                                            @foreach($product->selected_options as $option)
                                                                <li>
                                                                    {{$option->title}}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                @endif
                                                @if($product->pivot->range)
                                                    @php $range = json_decode($product->pivot->range) @endphp
                                                    <p class="m-0 mt-3"><strong>{{$product->calculator->name}}</strong></p>
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
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    {{$order->amount}}
                                    <svg width="15" height="15" viewBox="3 2 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <g id="euro_symbol_24px">
                                            <path id="icon/action/euro_symbol_24px"
                                                  d="M15 18.5C12.49 18.5 10.32 17.08 9.24 15H15V13H8.58C8.53 12.67 8.5 12.34 8.5 12C8.5 11.66 8.53 11.33 8.58 11H15V9H9.24C10.32 6.92 12.5 5.5 15 5.5C16.61 5.5 18.09 6.09 19.23 7.07L21 5.3C19.41 3.87 17.3 3 15 3C11.08 3 7.76 5.51 6.52 9H3V11H6.06C6.02 11.33 6 11.66 6 12C6 12.34 6.02 12.67 6.06 13H3V15H6.52C7.76 18.49 11.08 21 15 21C17.31 21 19.41 20.13 21 18.7L19.22 16.93C18.09 17.91 16.62 18.5 15 18.5Z"
                                                  fill="currentColor" fill-opacity="1"/>
                                        </g>
                                    </svg>
                                </td>
                                <td>{{$order->status}}</td>
                                <td>{{$order->bonus()}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
