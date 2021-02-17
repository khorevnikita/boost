@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 mt-5">
                <div class="alert alert-danger">
                    <p>Payment declined. You may see more information about payment details on your <a href="{{url("home")}}">home page</a> or try again later.
                    </p>
                    @if($order)
                        <a href="{{url("order/$order->id/pay")}}" class="btn btn-secondary">Try again</a>
                        {{--<ul class="list-unstyled">
                            <li>ID: <strong>{{$order->id}}</strong></li>
                            <li>Status: <strong>{{$order->status}}</strong></li>
                            <li></li>
                        </ul>--}}
                    @endif

                    {{--@if($data)
                        <ul>
                            @foreach($data as $key=>$v)
                                <li>{{$key}} - {{$v}}</li>
                            @endforeach
                        </ul>
                    @endif--}}
                </div>

            </div>
        </div>
    </div>
@endsection
