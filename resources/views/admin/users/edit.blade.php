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
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control" name="role" id="role">
                                    <option @if($user->role == "user") selected @endif value="user">User</option>
                                    <option @if($user->role == "manager") selected @endif value="manager">Manager</option>
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
