@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Add a category to <strong>{{$game->title}}</strong>
                    </div>

                    <div class="card-body">
                        <form action="{{url("/admin/categories")}}" method="post">
                            @csrf
                            <input type="hidden" name="game_id" value="{{$game->id}}">
                            <div class="form-group">
                                <label for="title">Category title</label>
                                <input id="title" type="text" class="form-control" name="title">
                                @error("title")
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input id="description" type="text" class="form-control" name="description">
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
