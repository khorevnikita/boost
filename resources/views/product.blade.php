@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-8">
                <div class="text-center bg-dark pt-5 pb-5 text-white">
                    <h2>{{$product->title}}</h2>
                </div>
                <div class="pt-5 pb-5">
                    {!! $product->short_description !!}
                </div>
                @if($product->calculator)
                    <div class="calculator row mb-5">
                        <div class="col-12">
                            <h4>{{$product->calculator->name}}</h4>
                        </div>
                        <div class="col">
                            <label>{{$product->calculator->min_title}}</label>
                            <input value="{{$product->pivot?$product->pivot->range->from:$product->calculator->min_value}}" type="number" id="slider-from" class="form-control">
                        </div>
                        <div class="col text-center"><p>{{$product->calculator->description}}</p></div>
                        <div class="col">
                            <label>{{$product->calculator->max_title}}</label>
                            <input value="{{$product->pivot?$product->pivot->range->to:$product->calculator->max_value}}" type="number" id="slider-to" class="form-control">
                        </div>
                        <div class="col-12 mt-3">
                            <div id="slider-range"></div>
                        </div>
                    </div>
                @endif
                <div>
                    {!! $product->description !!}
                </div>
            </div>
            <div class="col-12 col-sm-4">
                @if($product->images->count() > 0)
                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($product->images as $k=>$image)
                                <div class="carousel-item @if(!$k) active @endif ">
                                    <img class="d-block w-100" src="{{$image->url}}" alt="">
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                @endif

                <div class="text-center">
                    @include('particles.product_rating', ['product' => $product,'vote'=>true,])
                </div>

                <div id="app" class="mt-5">
                    <product-order @if($product->calculator) :calculator="{{$product->calculator}}" @endif :product="{{$product}}" :options="{{$product->options}}"></product-order>
                </div>
            </div>
        </div>
        <div style="clear: both"></div>
        @if($crosses->count()>0)
            <h4 class="mt-4">You may be interested in:</h4>
            <div class="row row-eq-height mt-5">
                @foreach($crosses as $cross)
                    <div class="col-4">
                        <div style="cursor: pointer" data-href="{{url("/".$cross->category->game_id."/$cross->id")}}" class="media text-muted more-item">
                            <div>
                                <img style="width: 110px" src="{{$cross->banner}}" class="mr-3" alt="...">
                                <div class="text-center">
                                    @include('particles.product_rating', ['product' => $cross,'vote'=>false])
                                </div>
                            </div>
                            <div class="media-body">
                                <p class="mt-0">{{$cross->title}}</p>
                                <p>{{$cross->price}} <img style="width: 15px" src="/images/icons/euro.svg"/></p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($recentlyViewedItems->count()>0)
            <h4 class="mt-4">Recently viewed items</h4>
            <div class="row row-eq-height mt-5">
                @foreach($recentlyViewedItems as $item)
                    <div class="col-4">
                        <div style="cursor: pointer" data-href="{{url("/".$item->category->game_id."/$item->id")}}" class="media text-muted more-item">
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
