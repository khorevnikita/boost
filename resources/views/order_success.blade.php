@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 mt-5">
                <div class="alert alert-success">
                    Payment proceeded. You may see more information about payment details on your <a href="{{url("home")}}">home page</a>
                    @if($order)
                        <ul class="list-unstyled">
                            <li>Order ID: <strong>{{$order->id}}</strong></li>
                            <li>Date: <strong>{{$order->payed_at}}</strong></li>
                            <li>Status: <strong>{{$order->status}}</strong></li>
                        </ul>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
