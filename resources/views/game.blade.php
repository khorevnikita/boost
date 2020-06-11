@extends('layouts.app')

@section('content')
    @if($game->actual_banner)
        @include('particles.banner_item', ['banner' => $game->actual_banner])
    @else
        <div class="text-center bg-dark pt-5 pb-5 text-white banner">
            <h2>{{$game->title}}</h2>
        </div>
    @endif
    <div class="container">
        <div class="mt-5">
            <game-product-list :currency="'{{$currency}}'" :game="{{$game}}"></game-product-list>
        </div>
        <div style="clear: both"></div>


        <div class="text-center pt-5 pb-5">
            <h4>{!! $game->description !!}</h4>
        </div>


        @if($recentlyViewedItems->count()>0)
            <h4 class="mt-4">Recently viewed items</h4>
            <div class="row row-eq-height mt-5">
                @foreach($recentlyViewedItems as $item)
                    <div class="col-12 col-md-4 mt-4">
                        <div style="cursor: pointer" data-href="{{url($item->url)}}" class="media text-muted more-item">
                            <div>
                                <img style="width: 110px" src="{{$item->banner}}" class="mr-3" alt="...">
                                <div class="text-center">
                                    @include('particles.product_rating', ['product' => $item,'vote'=>false])
                                </div>
                            </div>
                            <div class="media-body">
                                <p class="mt-0">{{$item->title}}</p>
                                <p>{{$item->price}}
                                    @if($currency=="usd")
                                        $
                                    @else
                                        <img style="width: 15px" src="/images/icons/euro.svg"/>
                                    @endif
                                </p>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
