@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Create a game
                    </div>

                    <div class="card-body">
                        <form action="{{url("/admin/games")}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="title">Game title</label>
                                <input id="title" type="text" class="form-control" name="title">
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
