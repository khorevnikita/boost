@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h4>My bonus</h4>
                <p class="text-primary">{{$user->bonus}} <img src="/images/icons/euro.svg"></p>
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
                                                    <ul>
                                                        @foreach($product->selected_options as $option)
                                                            <li>
                                                                {{$option->title}}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    {{$order->amount}} <img src="/images/icons/euro.svg">
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
