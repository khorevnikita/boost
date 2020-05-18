@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{$option->title}}
                    </div>

                    <div class="card-body">
                        <h4>General information</h4>
                        <form action="{{url("/admin/options/$option->id")}}" method="post" class="mt-3">
                            @csrf
                            @method("PUT")
                            <div class="form-group">
                                <label for="title">Option title</label>
                                <input id="title" type="text" class="form-control" name="title" value="{{$option->title}}">
                            </div>
                            <div class="form-group">
                                <label for="short_description">Short description</label>
                                <textarea id="short_description" type="text" class="form-control" name="short_description">{{$option->short_description}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input id="price" type="text" class="form-control" name="price" value="{{$option->price}}">
                            </div>
                            <div class="form-group">
                                <label for="type">Price type</label>
                                <select id="type" class="form-control" name="type">
                                    <option @if($option->type='abs') selected @endif value="abs">Absolute</option>
                                    <option @if($option->type='percent') selected @endif value="percent">Percent</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>
                        <hr>
                        <div class="form-group">
                            <form action="{{url("admin/options/$option->id")}}" method="post">
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
