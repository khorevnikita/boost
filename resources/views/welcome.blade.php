@extends('layouts.app')

@section('content')
    {{--@if($banner)
        @include('particles.banner_item', ['banner' => $banner])
    @else
        <div class="text-center pt-5 pb-5 text-white banner">
            <h2>Boost your skill. Team up with PROes. <br> Be invincible and enjoy the game every moment.
            </h2>
        </div>
    @endif--}}
    <div class="main-banner" style="background-image: url(/images/main_bg.png);">
        <div class="bg-tone">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h1>Shadowlands has arrived.
                            Check out our brilliant offers
                            to become worlds leading player</h1>
                        <div style="  margin-top: 70px;" class="align-items-center d-flex">
                            <a target="_blank" href="https://www.trustpilot.com/review/boostmytoon.com" class="btn btn-primary b-r-30">Find out more</a>
                            <div class="pilot">
                                <p>Rated 5 stars on Trust<span>pilot</span></p>
                                <img src="/images/trustpilotstar.png">
                                <img src="/images/trustpilotstar.png">
                                <img src="/images/trustpilotstar.png">
                                <img src="/images/trustpilotstar.png">
                                <img src="/images/trustpilotstar.png">
                            </div>
                        </div>
                    </div>
                    <div class="d-none d-md-block col-md-6">
                        <img style="max-width: 100%" src="/images/main_target_img.png">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div style="margin-top: -110px">
            <p class="mt-3"><span class="point-primary"></span>&nbsp;Choose your game</p>
            <div class="row" id="games">
                @foreach($games as $game)
                    <div class="col d-flex align-items-center" style="padding: 0 5px">
                        <a href="{{url($game->rewrite)}}" class="btn btn-block btn-main-game">
                            @if($game->button_icon_url)
                                <img class="game-icon" src="{{$game->button_icon_url}}"/>
                            @endif
                            {{$game->title}}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        @foreach($games as $game)
            @if($game->topDeals()->count())
                <p class="mt-5 d-flex justify-content-between align-items-center">
                    <span><span class="text-primary">{{$game->title}}</span> top deals</span>
                    <a class="float-right btn btn-outline-secondary b-r-30" href="{{url("/$game->rewrite")}}">
                        All {{$game->title}} deals
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <g id="arrow_forward_ios_24px">
                                <path id="icon/navigation/arrow_forward_ios_24px"
                                      d="M6.16504 20.13L7.93504 21.9L17.835 12L7.93504 2.10001L6.16504 3.87001L14.295 12L6.16504 20.13H6.16504Z" fill="currentColor"
                                      fill-opacity="1"/>
                            </g>
                        </svg>
                    </a>
                </p>
                <div style="clear:both"></div>
                <div class="row">
                    @foreach($game->topDeals() as $product)
                        <div class="col-12 col-sm-6 col-md-4 mt-4">
                            @include("particles.product_item",["product"=>$product])
                        </div>
                    @endforeach
                </div>

            @endif
        @endforeach


        @if($page)
            <div class="mt-5">
                {!! $page->text !!}
            </div>
        @endif
    </div>
@endsection
