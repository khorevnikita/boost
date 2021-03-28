@extends('layouts.app')
@push('seo')
    <title>{{$product->seo_title?:$product->title}}</title>
    <meta name="description" content="{{$product->seo_description?:$product->short_description}}">
    @if($product->seo_keys)
        <meta name="Keywords" content="{{$product->seo_keys}}">
    @endif
@endpush
@section('content')
    {{--}}@if($game->actual_banner)
        @include('particles.banner_item', ['banner' => $game->actual_banner])
    @else
        <div class="text-center bg-dark pt-5 pb-5 text-white banner">
            <h2>{{$game->title}}</h2>
        </div>
    @endif--}}
    <div class="main-banner" style="background-image: url(/images/game_bg.png);height: 430px">
        <div class="bg-tone">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 d-flex justify-content-center flex-column">
                        @if($product->category)<span class="b-r-30 bg-primary text-white cat-span">{{$product->category->title}}</span>@endif
                        <h1>{{$game->title}}</h1>
                        <a href="{{url($game->rewrite)}}" class="btn btn-primary b-r-30 mt-5">All {{$game->title}} deals</a>
                    </div>
                    <div class="d-none d-md-block col-md-6">
                        <img style="max-width: 100%" src="/images/game_target_img.png">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container" style="    padding-bottom: 30px;">
        <div class="text-center d-sm-none mt-4">
            <h4>@if($product->category)<span class="text-primary">{{$product->category->title}}</span> - @endif {{$product->title}}</h4>
            <a href="{{url("/$game->rewrite")}}" class="btn btn-outline-secondary b-r-30 mt-3">
                Back to deals
                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <g id="arrow_forward_ios_24px">
                        <path id="icon/navigation/arrow_forward_ios_24px"
                              d="M6.16504 20.13L7.93504 21.9L17.835 12L7.93504 2.10001L6.16504 3.87001L14.295 12L6.16504 20.13H6.16504Z" fill="currentColor"
                              fill-opacity="1"/>
                    </g>
                </svg>
            </a>
        </div>
        {{--<div>
            @include('particles.product_rating', ['product' => $product,'vote'=>true,])
        </div>--}}

        <div id="app">
            <product-order
                @if($product->images) :images="{{$product->images}}" @endif

            @if($calculator) :calculator="{{$calculator}}" @endif
                :product="{{$product}}"
                :game="{{$game}}"
                :options="{{$product->getOptions()}}"
                :rate="'{{$rate}}'"
                :currency="'{{$currency}}'"></product-order>
        </div>

        <div style="clear: both"></div>
        @if($crosses->count()>0)
            <h4 class="mt-4">You may be interested in:</h4>
            <div class="row row-eq-height ">
                @foreach($crosses as $cross)
                    <div class="col-12 col-sm-4">
                        @include("particles.product_item",["product"=>$cross])
                    </div>
                @endforeach
            </div>
        @endif

        @if($recentlyViewedItems->count()>0)
            <h4 class="mt-4">Recently viewed items</h4>
            <div class="row row-eq-height">
                @foreach($recentlyViewedItems as $item)
                    <div class="col-12 col-sm-4">
                        @include("particles.product_item",["product"=>$item])
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @push("scripts")
        <script>
            $(".js-vote").click(function () {
                axios.post("/assessments", {value: $(this).data("value"), product_id: '{{$product->id}}'}).then(r => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thank you for voting',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }).catch(err => {
                    Swal.fire({
                        title: 'Error!',
                        text: 'To vote you need to log in',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                    //alert("To vote you need to log in")
                });
            });
            $(".more-item").click(function () {
                window.location.href = $(this).data("href")
            });
        </script>
    @endpush
@endsection
