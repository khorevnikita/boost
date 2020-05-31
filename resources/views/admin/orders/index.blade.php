@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Orders
                        <a class="float-right btn btn-primary" href="{{url("admin/orders/create")}}">Create an order</a>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Created at</td>
                                    <td>Payed at</td>
                                    <td>Amount</td>
                                    <td>Status</td>
                                    <td>User</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <select name="status" class="form-control js-filter">
                                            <option value="">All</option>
                                            <option value="new" @if(Request::input("status")=="new") selected @endif>New</option>
                                            <option value="formed" @if(Request::input("status")=="formed") selected @endif>Formed</option>
                                            <option value="payed" @if(Request::input("status")=="payed") selected @endif>Payed</option>
                                            <option value="declined" @if(Request::input("status")=="declined") selected @endif>Declined</option>
                                            <option value="done" @if(Request::input("status")=="done") selected @endif>Done</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input name="user" type="text" class="form-control js-filter" value="{{Request::input("user")}}">
                                    </td>
                                    <td>
                                        <button class="btn btn-primary js-search">Search</button>
                                    </td>
                                </tr>

                                </thead>
                                <tbody>
                                @if($orders->count()>0)
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{$order->id}}</td>
                                            <td>{{$order->created_at}}</td>
                                            <td>{{$order->payed_at}}</td>
                                            <td>{{$order->amount}} {{$order->currency}}</td>
                                            <td>{{$order->status}}</td>
                                            <td>
                                                @if($order->user)
                                                    {{$order->user->surname}} {{$order->user->name}} <br>
                                                    {{$order->user->email}} <br>
                                                    {{$order->user->phone}}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <a class="text-primary" href="{{url("admin/orders/$order->id")}}">More</a>
                                            </td>
                                        </tr>
                                    @endforeach

                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push("scripts")
        <script>
            $(".js-search").click(function () {
                var search = "?";
                for (var e of $(".js-filter")) {
                    search = search + e.name + "=" + e.value + "&";
                }
                window.location.href = "/admin/orders" + search
            })
        </script>
    @endpush
@endsection
