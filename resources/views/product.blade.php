@extends('layouts.app')

@section('content')
    <div class="container-fluid">
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

                <div id="app" class="mt-5">
                    <product-order :product="{{$product}}" :options="{{$product->options}}"></product-order>
                </div>
                {{--<h4 class="text-center">{{$product->price}} Е</h4>
                <button class="btn btn-primary btn-block">Add to cart</button>


                @if($product->options->count()>0)
                    <h4>Options</h4>
                    @foreach($product->options as $option)
                        <div class="form-check">
                            <input name="options[]" value="{{$option->id}}" type="checkbox" class="form-check-input" id="opt{{$option->id}}">
                            <label class="form-check-label" for="opt{{$option->id}}">{{$option->title}}</label>
                        </div>
                    @endforeach
                @endif--}}
            </div>
        </div>

    </div>
@endsection
<script>
    import ProductOrder from "../js/components/ProductOrder";

    export default {
        components: {ProductOrder}
    }
</script>
