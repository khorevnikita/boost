@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{$user->surname}} {{$user->name}}
                    </div>

                    <div class="card-body">
                        <h4>General information</h4>
                        <form action="{{url("/admin/users/$user->id")}}" method="post" class="mt-3">
                            @csrf
                            @method("PUT")
                            <div class="form-group">
                                <label for="surname">Surname</label>
                                <input id="surname" type="text" class="form-control" name="surname" value="{{$user->surname}}">
                            </div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input id="name" type="text" class="form-control" name="name" value="{{$user->name}}">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" type="email" class="form-control" name="email" value="{{$user->email}}">
                                @error("email")
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input id="phone" type="text" class="form-control" name="phone" value="{{$user->phone}}">
                            </div>
                            <div class="form-group">
                                <label for="email">Skype</label>
                                <input id="email" type="text" class="form-control" name="skype" value="{{$user->skype}}">
                            </div>
                            <div class="form-group">
                                <label for="phone">Discord</label>
                                <input id="phone" type="text" class="form-control" name="discord" value="{{$user->discord}}">
                            </div>

                            <div class="form-group">
                                <label for="bonus">Bonus</label>
                                <input id="bonus" type="text" class="form-control" name="bonus" value="{{$user->bonus}}">
                            </div>


                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control" name="role" id="role">
                                    <option @if($user->role == "user") selected @endif value="user">User</option>
                                    <option @if($user->role == "content") selected @endif value="content">Content-managers</option>
                                    <option @if($user->role == "sales") selected @endif value="sales">Sales-Manager</option>
                                    <option @if($user->role == "admin") selected @endif value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>


                        </form>
                        {{--<hr>
                        <div class="form-group">
                            <form action="{{url("admin/users/$user->id")}}" method="post">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn btn-danger float-right">Delete</button>
                            </form>
                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
