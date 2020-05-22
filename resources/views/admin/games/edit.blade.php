@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        {{$game->title}}
                    </div>

                    <div class="card-body">
                        <h4>General information</h4>
                        <form action="{{url("/admin/games/$game->id")}}" method="post" class="mt-3">
                            @csrf
                            @method("PUT")
                            <div class="form-group">
                                <label for="title">Game title</label>
                                <input id="title" type="text" class="form-control" name="title" value="{{$game->title}}">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input id="description" type="text" class="form-control" name="description" value="{{$game->description}}">
                            </div>
                            <div class="form-group" id="app">
                                @if($game->banner)
                                    <img src="{{$game->banner_url}}" class="img-fluid">
                                @endif
                                <button class="btn btn-outline-primary mt-3" id="pick-avatar">Upload banner</button>
                                <image-cropper
                                    @uploaded="reload()"
                                    trigger="#pick-avatar"
                                    :labels="{submit:'Save',cancel:'Cancel'}"
                                    upload-url="/admin/games/{{$game->id}}/banner"
                                    :output-options="{width:1400,height:300}"
                                    :cropper-options="{aspectRatio: 16/9,autoCropArea: 1,viewMode: 1, movable: false, zoomable: false}"
                                ></image-cropper>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>
                        <hr>
                        <h4>Categories</h4>
                        @if($game->categories->count()>0)
                            <ul class="list-group list-group-item-action ">
                                @foreach($game->categories as $category)
                                    <li class="list-group-item">
                                        <a href="{{url("/admin/categories/$category->id/edit")}}">{{$category->title}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="alert alert-primary">{{$game->title}} has no categories. Create the first</div>
                        @endif
                        <a class="btn btn-primary mt-3" href="{{url("/admin/categories/create?game_id=$game->id")}}">Add a category</a>

                        <hr>
                        <div class="form-group">
                            <form action="{{url("admin/games/$game->id")}}" method="post">
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
