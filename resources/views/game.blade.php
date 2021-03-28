@extends('layouts.app')

@section('content')
    {{--@if($game->actual_banner)
        @include('particles.banner_item', ['banner' => $game->actual_banner])
    @else
        <div class="text-center bg-dark pt-5 pb-5 text-white banner">
            <h2>{{$game->title}}</h2>
        </div>
    @endif--}}
    <div class="main-banner" style="background-image: url(/images/game_bg.png); height: 430px">
        <div class="bg-tone">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                        <h1>{{$game->title}}</h1>
                    </div>
                    <div class="d-none d-md-block col-md-6">
                        <img style="max-width: 100%" src="/images/game_target_img.png">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container" id="app" style="padding-bottom: 30px;">
        <p class="mt-3"><span class="text-primary">{{$game->title}}</span> items</p>
        <game-product-list :currency="'{{$currency}}'" :game="{{$game}}"></game-product-list>
        <div style="clear: both"></div>


        <div class="text-center pt-5 pb-5">
            <h4>{!! $game->description !!}</h4>
        </div>


        @if($recentlyViewedItems->count()>0)
            <h4 class="mt-4">Recently viewed items</h4>
            <div class="row row-eq-height mt-5">
                @foreach($recentlyViewedItems as $item)
                    <div class="col-12 col-sm-4">
                        @include("particles.product_item",["product"=>$item])
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
