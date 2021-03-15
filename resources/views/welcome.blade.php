@extends('layouts.app')

@section('content')
    @if($banner)
        @include('particles.banner_item', ['banner' => $banner])
    @else
        <div class="text-center pt-5 pb-5 text-white banner">
            <h2>Boost your skill. Team up with PROes. <br> Be invincible and enjoy the game every moment.
            </h2>
        </div>
    @endif
    <div class="container">
        <p class="mt-3"><span class="point-primary"></span>&nbsp;Choose your game</p>
        <div class="row">
            @foreach($games as $game)
                <div class="col-6 col-sm-3 ">
                    <a href="{{url($game->rewrite)}}" class="btn btn-block btn-main-game">
                        {{$game->title}}
                    </a>
                </div>
            @endforeach

        </div>
        @foreach($games as $game)
            @if($game->topDeals()->count())
                <p class="mt-5">
                    <span class="text-primary">{{$game->title}}</span> top deals
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
                        <div class="col-12 col-sm-6 mt-4">
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
