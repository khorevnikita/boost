@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        @if(0)
            <img src="{{$game->banner_url}}" class="img-fluid">
        @endif
        <div class="text-center bg-dark pt-5 pb-5 text-white">
            <h2>{{$game->title}}</h2>
        </div>
        <div class="text-center pt-5 pb-5">
            <h4>{{$game->description}}</h4>
        </div>
        <div class="row">
            @foreach($products as $product)
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
    </div>
@endsection
