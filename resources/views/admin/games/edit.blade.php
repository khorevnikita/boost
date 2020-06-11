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
                                @error("title")
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="rewrite">Rewrite name</label>
                                <input id="rewrite" type="text" class="form-control" name="rewrite" value="{{$game->rewrite}}">
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" class="form-control" name="description">{{$game->description}}</textarea>
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
    @push("scripts")
        <script>

            document.addEventListener("DOMContentLoaded", function () {
                $('#description').summernote({
                    height: 200,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ]
                });
            });
        </script>
    @endpush
@endsection
