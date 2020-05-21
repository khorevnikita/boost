@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        @if(0)
            <img src="{{$game->banner_url}}" class="img-fluid">
        @endif
        <div class="text-center bg-dark pt-5 pb-5 text-white">
            <h2>{{$game->title}}</h2>
        </div>
        <div class="text-center pt-5 pb-5">
            <h4>{{$game->description}}</h4>
        </div>
        {{--<form action="{{url("/$game->id")}}" method="get">
            <div class="row">
                <div class="col-6">
                    <div class="input-group mb-2 mr-sm-2">
                        <input type="text" class="form-control" id="search" name="search" placeholder="Search" value="{{Request::input("search")}}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <img style="    width: 20px" src="/images/icons/search.svg">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-primary js-toggle-category">Categories</button>
                </div>
                <div class="col">
                    <div class="form-check form-check-inline">
                        <input @if(Request::has("sort_by") && Request::input("sort_by") == 'price') checked @endif class="form-check-input js-sort-by" type="radio" name="sort_by" id="sort_by_price" value="price">
                        <label class="form-check-label" for="sort_by_price">Price</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input @if(Request::has("sort_by") && Request::input("sort_by") == 'popularity') checked @endif class="form-check-input js-sort-by" type="radio" name="sort_by" id="sort_by_popularity" value="popularity">
                        <label class="form-check-label" for="sort_by_popularity">Popularity</label>
                    </div>
                </div>
            </div>
            <div class="mt-3 js-category-list @if(!Request::has("show_category")) d-none @endif ">
                @foreach($game->categories as $category)
                    <a style="font-size: 22px;" href="{{url("/$game->id?category_id=$category->id&show_category=true")}}"><span class="badge badge-primary">{{$category->title}}</span></a>
                @endforeach
            </div>
        </form>
        <div class="row mt-5">
            @foreach($products as $product)
                <div class="col-12 col-sm-6">
                    <a href="{{url("/".$product->category->game_id."/$product->id")}}">
                        <div style="background-image: url('{{$product->banner}}')" class="product-item">
                            <span style="margin: 10px" class="badge badge-secondary">{{$product->category->title}}</span>
                            @if($product->is_hot)
                                <span style="margin: 10px" class="badge badge-danger float-right">HOT</span>
                            @endif
                            @if($product->is_new)
                                <span style="margin: 10px" class="badge badge-danger float-right">NEW</span>
                            @endif
                            <div class="product-item-footer">
                                <span class="float-right">{{$product->price}}</span>
                                {{$product->title}}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>--}}
        <game-product-list :game="{{$game}}"></game-product-list>
    </div>
@endsection
