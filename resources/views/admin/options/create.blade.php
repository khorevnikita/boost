@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Create an option
                    </div>

                    <div class="card-body">
                        <form action="{{url("/admin/options")}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="title">Option title</label>
                                <input id="title" type="text" class="form-control" name="title">
                                @error('title')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="short_description">Short description</label>
                                <textarea id="short_description" type="text" class="form-control" name="short_description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input id="price" type="text" class="form-control" name="price">
                                @error('price')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="type">Price type</label>
                                <select id="type" class="form-control" name="type">
                                    <option value="abs">Absolute</option>
                                    <option value="percent">Percent</option>
                                </select>
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
