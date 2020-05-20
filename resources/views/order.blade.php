@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if(!$order)
                    <div class="alert alert-primary"> You have no active orders</div>
                @else
                    <h4>Order #{{$order->id}} ({{$order->created_at}})</h4>
                    <div class="row">
                        <div class="col-6">
                            <div class="list-group" id="list-tab" role="tablist">
                                @foreach($order->products as $k=>$product)
                                    <a class="list-group-item list-group-item-action @if(!$k) active @endif" id="list-home-list" data-toggle="list" href="#list-{{$k}}" role="tab"
                                       aria-controls="home">
                                        {{$product->title}} <span class="float-right">{{$product->price}}<img style="width: 15px" src="/images/icons/euro.svg"></span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="tab-content" id="nav-tabContent">
                                @foreach($order->products as $key=>$product)
                                    <div class="tab-pane fade show  @if(!$key) active @endif" id="list-{{$key}}" role="tabpanel" aria-labelledby="list-home-list">
                                        <h4>Options:</h4>
                                        <ol>
                                            @foreach($product->selected_options as $option)
                                                <li>
                                                    {{$option->title}}
                                                    <span class="float-right">{{$option->price}}

                                                        @if($option->type=="abs")
                                                            <img style="width: 15px" src="/images/icons/euro.svg">
                                                        @else
                                                            %
                                                        @endif
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ol>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary float-right mt-3">Pay</button>
                    <h4 class="text-primary mt-3">Price:{{$commonPrice}}<img src="/images/icons/euro.svg"></h4>
                @endif
            </div>
        </div>
    </div>
@endsection
<script>

</script>
