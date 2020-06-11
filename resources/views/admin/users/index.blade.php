@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Users list
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <td>Name</td>
                                    <td>Email</td>
                                    <td>Phone</td>
                                    <td>Role</td>
                                    <td>Registered at</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input name="name" class="form-control js-filter" value="{{Request::input("name")}}">
                                    </td>
                                    <td>
                                        <input name="email" class="form-control js-filter" value="{{Request::input("email")}}">
                                    </td>
                                    <td>
                                        <input name="phone" class="form-control js-filter" value="{{Request::input("phone")}}">
                                    </td>
                                    <td>
                                        <select name="role" class="form-control js-filter">
                                            <option value="">All</option>
                                            <option @if(Request::input("role")=='user') selected @endif value="user">Client</option>
                                            <option @if(Request::input("role")=='admin') selected @endif value="admin">Administrator</option>
                                        </select>
                                    </td>
                                    <td></td>
                                    <td>
                                        <button class="btn btn-primary js-admin-search">Search</button>
                                    </td>
                                </tr>
                                </thead>
                                <tbody>
                                @if($users->count()>0)
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{$user->surname}} {{$user->name}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>{{$user->phone}}</td>
                                            <td>{{$user->role}}</td>
                                            <td>{{$user->created_at}}</td>
                                            <td>
                                                <a href="{{url("admin/users/$user->id/edit")}}" class="btn btn-primary">
                                                    More
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            {{$users->withQueryString()->links()}}
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
                window.location.href = "/admin/users" + search
            })
        </script>
    @endpush
@endsection
