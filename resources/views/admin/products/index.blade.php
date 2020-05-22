@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <a href="{{url("/admin/products/create")}}" class="btn btn-primary float-right">
                            Add a product
                        </a>
                        Product list
                    </div>
                    <div class="card-body">
                        @if($products->count()>0)
                            <ul class="list-group">
                                @foreach($products as $product)
                                    <li class="list-group-item list-group-item-action">
                                        <a href="{{url("/admin/products/$product->id/edit")}}">
                                            {{$product->title}} ({{$product->category->game->title}} - {{$product->category->title}})
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="alert alert-primary">No products found. Create the first</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
