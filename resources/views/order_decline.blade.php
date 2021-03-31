@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12" style="margin-top: 90px">
                <h3>Payment declined.</h3>
                <h4> You may see more information about payment details on your <a href="{{url("home")}}">home page</a> or try again later.</h4>
                @if($order)
                    <a href="{{url("order/$order->id/pay")}}" class="btn btn-primary">Try again</a>
                @endif
                <p>or contact us via <span class="text-primary">info@boostmytoon.com</span> to get your items</p>
            </div>
        </div>
    </div>
@endsection
