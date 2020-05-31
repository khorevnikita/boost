@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="text-center bg-dark pt-5 pb-5 text-white">
            <h2>{{$game->title}}</h2>
        </div>
        <div class="text-center pt-5 pb-5">
            <h4>{{$game->description}}</h4>
        </div>

        <game-product-list :game="{{$game}}"></game-product-list>

        <div style="clear: both"></div>
        @if($recentlyViewedItems->count()>0)
            <h4 class="mt-4">Recently viewed items</h4>
            <div class="row row-eq-height mt-5">
                @foreach($recentlyViewedItems as $item)
                    <div class="col-4">
                        <div style="cursor: pointer" data-href="{{url($item->url)}}" class="media text-muted more-item">
                            <div>
                                <img style="width: 110px" src="{{$item->banner}}" class="mr-3" alt="...">
                                <div class="text-center">
                                    @include('particles.product_rating', ['product' => $item,'vote'=>false])
                                </div>
                            </div>
                            <div class="media-body">
                                <p class="mt-0">{{$item->title}}</p>
                                <p>{{$item->price}} <img style="width: 15px" src="/images/icons/euro.svg"/></p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
