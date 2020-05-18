@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{$category->game->title}} - <strong>{{$category->title}}</strong>
                    </div>

                    <div class="card-body">
                        <h4>General information</h4>
                        <form action="{{url("/admin/categories/$category->id")}}" method="post" class="mt-3">
                            @csrf
                            @method("PUT")
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input id="title" type="text" class="form-control" name="title" value="{{$category->title}}">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input id="description" type="text" class="form-control" name="description" value="{{$category->description}}">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>

                        <h4>Products of the category</h4>
                        @if($category->products->count()>0)
                            <ul>
                                @foreach($category->products as $product)
                                    <li>
                                        <a href="{{url("/admin/products/$product->id/edit")}}">
                                            {{$product->title}}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="alert alert-primary">No products in the category. Create the first</div>
                        @endif
                        <a class="btn btn-primary" href="{{url("admin/products/create?category_id=$category->id")}}">Add a product</a>

                        <hr>
                        <div class="form-group">
                            <form action="{{url("admin/categories/$category->id")}}" method="post">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn btn-danger float-right">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
