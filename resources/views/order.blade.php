@extends('layouts.app')

@section('content')
 <div class="container">
        <div class="row">
            <div class="col-12 mt-5">
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
                                        {{$product->title}}
                                        <span class="float-right">
                                            {{$product->price}}
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="euro_symbol_24px">
                                <path id="icon/action/euro_symbol_24px"
                                      d="M15 18.5C12.49 18.5 10.32 17.08 9.24 15H15V13H8.58C8.53 12.67 8.5 12.34 8.5 12C8.5 11.66 8.53 11.33 8.58 11H15V9H9.24C10.32 6.92 12.5 5.5 15 5.5C16.61 5.5 18.09 6.09 19.23 7.07L21 5.3C19.41 3.87 17.3 3 15 3C11.08 3 7.76 5.51 6.52 9H3V11H6.06C6.02 11.33 6 11.66 6 12C6 12.34 6.02 12.67 6.06 13H3V15H6.52C7.76 18.49 11.08 21 15 21C17.31 21 19.41 20.13 21 18.7L19.22 16.93C18.09 17.91 16.62 18.5 15 18.5Z"
                                      fill="white" fill-opacity="1"/>
                            </g>
                        </svg>
                                            <button data-type="product" data-product="{{$product->id}}" type="button" class="btn btn-link text-danger js-remove-item-from-order">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<g id="clear_24px">
<path id="icon/content/clear_24px" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41Z" fill="#e3342f"
      fill-opacity="1"/>
</g>
</svg>
                                            </button>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="tab-content" id="nav-tabContent">
                                @foreach($order->products as $key=>$product)
                                    <div class="tab-pane fade show  @if(!$key) active @endif" id="list-{{$key}}" role="tabpanel" aria-labelledby="list-home-list">
                                        <h4>Options:</h4>
                                        @if($product->selected_options)
                                            <ol>
                                                @foreach($product->selected_options as $option)
                                                    <li>
                                                        {{$option->title}}
                                                        <span class="float-right">{{$option->price}}
                                                            @if($option->type=="abs")
                                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="euro_symbol_24px">
                                <path id="icon/action/euro_symbol_24px"
                                      d="M15 18.5C12.49 18.5 10.32 17.08 9.24 15H15V13H8.58C8.53 12.67 8.5 12.34 8.5 12C8.5 11.66 8.53 11.33 8.58 11H15V9H9.24C10.32 6.92 12.5 5.5 15 5.5C16.61 5.5 18.09 6.09 19.23 7.07L21 5.3C19.41 3.87 17.3 3 15 3C11.08 3 7.76 5.51 6.52 9H3V11H6.06C6.02 11.33 6 11.66 6 12C6 12.34 6.02 12.67 6.06 13H3V15H6.52C7.76 18.49 11.08 21 15 21C17.31 21 19.41 20.13 21 18.7L19.22 16.93C18.09 17.91 16.62 18.5 15 18.5Z"
                                      fill="#212529" fill-opacity="1"/>
                            </g>
                        </svg>
                                                            @else
                                                                %
                                                            @endif
                                                        <button data-type="option" data-product="{{$product->id}}" data-option="{{$option->id}}" type="button"
                                                                class="btn btn-link text-danger js-remove-item-from-order">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<g id="clear_24px">
<path id="icon/content/clear_24px" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41Z" fill="#e3342f"
      fill-opacity="1"/>
</g>
</svg>
                                                        </button>
                                                    </span>
                                                        <div style="clear: both"></div>
                                                    </li>
                                                @endforeach
                                            </ol>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <h4 class="mt-3">Contact form</h4>
                    <form id="order-form" action="{{url("/api/orders/$order->id/form")}}">
                        <div class="row">
                            <div class="col form-group">
                                <input class=" form-control" name="surname" placeholder="Surname" value="{{$user->surname??""}}">
                            </div>
                            <div class="col form-group">
                                <input class=" form-control" name="name" placeholder="Name" value="{{$user->name??""}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col form-group">
                                <input class=" form-control" name="email" placeholder="E-mail" value="{{$user->email??""}}">
                            </div>
                            <div class="col form-group">
                                <input class=" form-control" name="phone" placeholder="Phone" value="{{$user->phone??""}}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary float-right mt-3">Pay</button>
                        <h4 class="mt-3">Common price: <span class="text-primary">{{$order->amount}}</span>
                            <svg style="margin-top: -5px;margin-left: -5px;" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="euro_symbol_24px">
                                    <path id="icon/action/euro_symbol_24px"
                                          d="M15 18.5C12.49 18.5 10.32 17.08 9.24 15H15V13H8.58C8.53 12.67 8.5 12.34 8.5 12C8.5 11.66 8.53 11.33 8.58 11H15V9H9.24C10.32 6.92 12.5 5.5 15 5.5C16.61 5.5 18.09 6.09 19.23 7.07L21 5.3C19.41 3.87 17.3 3 15 3C11.08 3 7.76 5.51 6.52 9H3V11H6.06C6.02 11.33 6 11.66 6 12C6 12.34 6.02 12.67 6.06 13H3V15H6.52C7.76 18.49 11.08 21 15 21C17.31 21 19.41 20.13 21 18.7L19.22 16.93C18.09 17.91 16.62 18.5 15 18.5Z"
                                          fill="#3490dc" fill-opacity="1"/>
                                </g>
                            </svg>
                        </h4>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
