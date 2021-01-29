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
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Title</td>
                                    <td>Category</td>
                                    <td>Game</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-control js-filter" name="id" value="{{Request::input("id")}}">
                                    </td>
                                    <td>
                                        <input class="form-control js-filter" name="title" value="{{Request::input("title")}}">
                                    </td>
                                    <td>
                                        <input class="form-control js-filter" name="category" value="{{Request::input("category")}}">
                                    </td>
                                    <td>
                                        <input class="form-control js-filter" name="game" value="{{Request::input("game")}}">
                                    </td>
                                    <td>
                                        <button class="btn btn-primary js-admin-search">Search</button>
                                    </td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{$product->id}}</td>
                                        <td>{{$product->title}}</td>
                                        <td>{{$product->category?$product->category->title:"-"}}</td>
                                        <td>{{($product->category&&$product->category->game)?$product->category->game->title:"-"}}</td>
                                        <td>
                                            <a href="{{url("/admin/products/$product->id/edit")}}">More</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{$products->withQueryString()->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push("scripts")
        <script>
            $(".js-admin-search").click(function () {
                var search = "?";
                for (var e of $(".js-filter")) {
                    search = search + e.name + "=" + e.value + "&";
                }
                window.location.href = "/admin/products" + search
            })
        </script>
    @endpush
@endsection
