@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Orders
                    </div>

                    <div class="card-body">
                        @if($orders->count()>0)
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
                                    </thead>
                                    <tbody>
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
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
