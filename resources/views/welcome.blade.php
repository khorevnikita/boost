@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="text-center pt-5 pb-5 text-white banner">
            <h2>Boost your skill. Team up with PROes. <br> Be invincible and enjoy the game every moment.
            </h2>
        </div>
        <div class="text-center pt-5 pb-5">
            <h4>Safe Boost and Carry Services in Your Favorite Games</h4>
            <h5>Choose game you want to get services in:</h5>
            <h4>
                @foreach($games as $game)
                    <a href="{{url($game->rewrite)}}"><span class="badge badge-primary">{{$game->title}}</span></a>
                @endforeach
            </h4>
        </div>

        @foreach($games as $game)
            @if($game->topDeals()->count())
                <h4 class="mt-5">{{$game->title}} top deals
                    <a style="text-decoration: none;    color: black;    font-size: 14px;" class="float-right" href="{{url("/$game->id")}}">
                        all {{$game->title}} deals
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="arrow_forward_ios_24px">
                                <path id="icon/navigation/arrow_forward_ios_24px" d="M6.16504 20.13L7.93504 21.9L17.835 12L7.93504 2.10001L6.16504 3.87001L14.295 12L6.16504 20.13H6.16504Z" fill="black" fill-opacity="1"/>
                            </g>
                        </svg>
                    </a>
                </h4>
            <div style="clear:both"></div>
                <div class="row">
                    @foreach($game->topDeals() as $product)
                        <div class="col-12 col-sm-6 mt-4">
                            <a href="{{url($product->url)}}">
                                <div style="background-image: url('{{$product->banner}}')" class="product-item">
                                    <span style="margin: 10px" class="badge badge-secondary">{{$product->category->title}}</span>
                                    @if($product->is_hot)
                                        <span style="margin: 10px" class="badge badge-danger float-right">HOT</span>
                                    @endif
                                    @if($product->is_new)
                                        <span style="margin: 10px" class="badge badge-danger float-right">NEW</span>
                                    @endif
                                    <div class="product-item-footer">
                                        <span class="float-right">
                                            {{$product->price}}
                                        <svg width="20" height="20" viewBox="3 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<g id="euro_symbol_24px">
<path id="icon/action/euro_symbol_24px" d="M15 18.5C12.49 18.5 10.32 17.08 9.24 15H15V13H8.58C8.53 12.67 8.5 12.34 8.5 12C8.5 11.66 8.53 11.33 8.58 11H15V9H9.24C10.32 6.92 12.5 5.5 15 5.5C16.61 5.5 18.09 6.09 19.23 7.07L21 5.3C19.41 3.87 17.3 3 15 3C11.08 3 7.76 5.51 6.52 9H3V11H6.06C6.02 11.33 6 11.66 6 12C6 12.34 6.02 12.67 6.06 13H3V15H6.52C7.76 18.49 11.08 21 15 21C17.31 21 19.41 20.13 21 18.7L19.22 16.93C18.09 17.91 16.62 18.5 15 18.5Z" fill="white" fill-opacity="1"/>
</g>
</svg>
                                        </span>
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
