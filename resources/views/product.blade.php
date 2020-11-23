@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="pt-5">
            <h2>{{$product->title}}</h2>
        </div>
        <div>
            @include('particles.product_rating', ['product' => $product,'vote'=>true,])
        </div>
        <div id="app">
            <product-order
                @if($product->images) :images="{{$product->images}}" @endif
                :currency="'{{$currency}}'"
                @if($calculator) :calculator="{{$calculator}}" @endif
                :product="{{$product}}"
                :options="{{$product->options}}"
                :rate="'{{$rate}}'"
            ></product-order>
        </div>

        <div style="clear: both"></div>
        @if($crosses->count()>0)
            <h4 class="mt-4">You may be interested in:</h4>
            <div class="row row-eq-height ">
                @foreach($crosses as $cross)
                    <div class="col-12 col-md-4 mt-4">
                        <div style="cursor: pointer" data-href="{{url($cross->url)}}" class="media text-muted more-item">
                            <div>
                                <img style="width: 110px" src="{{$cross->banner}}" class="mr-3" alt="...">
                                <div class="text-center">
                                    @include('particles.product_rating', ['product' => $cross,'vote'=>false])
                                </div>
                            </div>
                            <div class="media-body">
                                <p class="mt-0">{{$cross->title}}</p>
                                <p>{{$cross->price}}
                                    @if($currency=="usd")
                                        $
                                    @else
                                        <img style="width: 15px" src="/images/icons/euro.svg"/>
                                    @endif</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($recentlyViewedItems->count()>0)
            <h4 class="mt-4">Recently viewed items</h4>
            <div class="row row-eq-height">
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
