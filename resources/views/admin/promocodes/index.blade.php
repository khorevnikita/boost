@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <a href="{{url("/admin/promocodes/create")}}" class="btn btn-primary float-right">
                            Add a promo code
                        </a>
                        Promo code
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Title</td>
                                    <td>Value</td>
                                    <td>Currency</td>
                                    <td>Time</td>
                                    <td>Code</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-control js-filter" name="id" value="{{Request::input("id")}}">
                                    </td>
                                    <td>
                                        <input class="form-control js-filter" name="title" value="{{Request::input("title")}}">
                                    </td>
                                    <td>
                                        <input class="form-control js-filter" name="value" value="{{Request::input("value")}}">
                                    </td>
                                    <td>
                                        <input class="form-control js-filter" name="currency" value="{{Request::input("currency")}}">
                                    </td>
                                    <td>
                                        <input class="form-control js-filter" name="end_at" value="{{Request::input("end_at")}}">
                                    </td>
                                    <td>
                                        <input class="form-control js-filter" name="code" value="{{Request::input("code")}}">
                                    </td>

                                    <td>
                                        <button class="btn btn-primary js-admin-search">Search</button>
                                    </td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($promocodes as $promocode)
                                    <tr>
                                        <td>{{$promocode->id}}</td>
                                        <td>{{$promocode->title}}</td>
                                        <td>{{$promocode->value}}</td>
                                        <td>{{$promocode->currency}}</td>
                                        <td>{{$promocode->end_at}}</td>
                                        <td>{{$promocode->code}}</td>
                                        <td>
                                            <a href="{{url("/admin/promocodes/$promocode->id/edit")}}">More</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{$promocodes->withQueryString()->links()}}
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
                window.location.href = "/admin/promocodes" + search
            })
        </script>
    @endpush
@endsection
