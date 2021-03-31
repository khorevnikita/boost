@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12" style="margin-top: 90px">

                <h3>Payment proceeded.</h3>
                <h4>
                    You may see more information about payment details on your <a href="{{url("home")}}">home page</a>
                </h4>
                @if($order)
                    <ul class="list-unstyled">
                        <li>Order ID: <strong>{{$order->id}}</strong></li>
                        <li>Date: <strong>{{$order->payed_at}}</strong></li>
                        <li>Status: <strong>{{$order->status}}</strong></li>
                    </ul>
                @endif
                <p>Contact us via <span class="text-primary">info@boostmytoon.com</span> to get your items</p>

            </div>
        </div>
    </div>
@endsection
