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
                    @for($e=0;$e<$product->rating;$e++)
                        <a class="text-warning js-vote" data-value="{{$e+1}}" href="#" role="button">
                            <svg class="bi bi-star-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                        </a>
                    @endfor
                    @for($e=($product->rating);$e<5;$e++)
                        <a class="text-muted js-vote" data-value="{{$e+1}}" href="#" role="button">
                            <svg class="bi bi-star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.523-3.356c.329-.314.158-.888-.283-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767l-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288l1.847-3.658 1.846 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.564.564 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
                            </svg>
                        </a>
                    @endfor
                </div>

                <div id="app" class="mt-5">
                    <product-order :product="{{$product}}" :options="{{$product->options}}"></product-order>
                </div>
            </div>
        </div>
        <div style="clear: both"></div>
        @if($recentlyViewedItems)
            <h4 class="mt-4">Recently viewed items</h4>
            <div class="row mt-5">
                @foreach($recentlyViewedItems as $item)
                    <div class="col-4">
                        <a style="text-decoration: none" href="{{url("/".$item->category->game_id."/$item->id")}}" class=" media text-muted">
                            <img style="width: 110px" src="{{$item->banner}}" class="mr-3" alt="...">
                            <div class="media-body">
                                <p class="mt-0">{{$item->title}}</p>
                                <p>{{$item->price}} <img style="width: 15px" src="/images/icons/euro.svg"/></p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @push("scripts")
        <script>
            $(".js-vote").click(function () {
                axios.post("/assessments", {value: $(this).data("value"), product_id: '{{$product->id}}'}).then(r => {
                    alert("Success")
                });
            })
        </script>
    @endpush
@endsection
