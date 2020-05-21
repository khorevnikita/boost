@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="text-center bg-dark pt-5 pb-5 text-white">
            <h2>Welcome to our Awesome Site!</h2>
        </div>
        <div class="text-center pt-5 pb-5">
            <h4>Safe Boost and Carry Services in Your Favorite Games</h4>
            <h5>Choose game you want to get services in:</h5>
            <h4>
                @foreach($games as $game)
                    <a href="{{url("/$game->id")}}"><span class="badge badge-primary">{{$game->title}}</span></a>
                @endforeach
            </h4>
        </div>

        @foreach($games as $game)
            @if($game->topDeals()->count())
                <h4 class="mt-5">{{$game->title}} top deals
                <a style="text-decoration: none;    color: black;    font-size: 14px;" class="float-right" href="{{url("/$game->id")}}">all {{$game->title}} deals &rAarr;</a>
                </h4>
                <div class="row">
                    @foreach($game->topDeals() as $product)
                        <div class="col-12 col-sm-6">
                            <a href="{{url("/".$product->category->game_id."/$product->id")}}">
                                <div style="background-image: url('{{$product->banner}}')" class="product-item">
                                    <span style="margin: 10px" class="badge badge-secondary">{{$product->category->title}}</span>
                                    @if($product->is_hot)
                                        <span style="margin: 10px" class="badge badge-danger float-right">HOT</span>
                                    @endif
                                    @if($product->is_new)
                                        <span style="margin: 10px" class="badge badge-danger float-right">NEW</span>
                                    @endif
                                    <div class="product-item-footer">
                                        <span class="float-right">{{$product->price}}</span>
                                        {{$product->title}}
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>
@endsection
