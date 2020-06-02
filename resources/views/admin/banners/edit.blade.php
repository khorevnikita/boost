@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        Create a banner
                    </div>

                    <div class="card-body">
                        <h4>Preview</h4>
                        @include('particles.banner_item', ['banner' => $banner])
                        <form class="mt-3" action="{{url("/admin/banners/$banner->id")}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method("PUT")
                            <div class="form-group">
                                <label for="bg">Background</label>
                                <input id="bg" type="file" name="background">
                            </div>
                            <div class="form-group">
                                <label for="object_image">Object image</label>
                                <input id="object_image" type="file" name="object_image">
                            </div>
                            <div class="form-group">
                                <label for="text">Text</label>
                                <textarea id="text" name="text" class="form-control">{{$banner->text}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="action_title">Action title</label>
                                <input id="action_title" name="action_title" class="form-control" value="{{$banner->action_title}}">
                            </div>
                            <div class="form-group">
                                <label for="action_url">Action url</label>
                                <input id="action_url" name="action_url" class="form-control" value="{{$banner->action_url}}">
                            </div>
                            <div class="form-group">
                                <label for="game_id">Place</label>
                                <select id="game_id" name="game_id" class="form-control">
                                    <option value="">Main banner</option>
                                    @foreach($games as $game)
                                        <option value="{{$game->id}}" @if($banner->game_id == $game->id) selected @endif>{{$game->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="published" value="1" @if($banner->published) checked @endif >
                                    Published
                                </label>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
