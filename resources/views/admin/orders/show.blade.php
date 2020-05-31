@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Order #{{$order->id}}
                    </div>
                    <div class="card-body">

                        <h4>User
                            <a href="{{url("admin/users/$user->id/edit")}}">
                                <svg class="bi bi-pencil" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                          d="M11.293 1.293a1 1 0 0 1 1.414 0l2 2a1 1 0 0 1 0 1.414l-9 9a1 1 0 0 1-.39.242l-3 1a1 1 0 0 1-1.266-1.265l1-3a1 1 0 0 1 .242-.391l9-9zM12 2l2 2-9 9-3 1 1-3 9-9z"/>
                                    <path fill-rule="evenodd"
                                          d="M12.146 6.354l-2.5-2.5.708-.708 2.5 2.5-.707.708zM3 10v.5a.5.5 0 0 0 .5.5H4v.5a.5.5 0 0 0 .5.5H5v.5a.5.5 0 0 0 .5.5H6v-1.5a.5.5 0 0 0-.5-.5H5v-.5a.5.5 0 0 0-.5-.5H3z"/>
                                </svg>
                            </a>
                        </h4>
                        <ul class="list-unstyled">
                            <li>Name: <strong>{{$user->surname}} {{$user->name}}</strong></li>
                            <li>Email: <strong>{{$user->email}}</strong></li>
                            <li>Phone: <strong>{{$user->phone}}</strong></li>
                            <li>Bonus: <strong>{{$user->bonus}}</strong></li>
                            <li>Email verification:
                                @if($user->email_verified_at)
                                    <strong class="text-success">Yes</strong>
                                @else
                                    <strong class="text-danger">No</strong>
                                @endif
                            </li>
                        </ul>

                        <h4>Order
                            <a href="{{url("admin/orders/$order->id/edit")}}">
                                <svg class="bi bi-pencil" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                          d="M11.293 1.293a1 1 0 0 1 1.414 0l2 2a1 1 0 0 1 0 1.414l-9 9a1 1 0 0 1-.39.242l-3 1a1 1 0 0 1-1.266-1.265l1-3a1 1 0 0 1 .242-.391l9-9zM12 2l2 2-9 9-3 1 1-3 9-9z"/>
                                    <path fill-rule="evenodd"
                                          d="M12.146 6.354l-2.5-2.5.708-.708 2.5 2.5-.707.708zM3 10v.5a.5.5 0 0 0 .5.5H4v.5a.5.5 0 0 0 .5.5H5v.5a.5.5 0 0 0 .5.5H6v-1.5a.5.5 0 0 0-.5-.5H5v-.5a.5.5 0 0 0-.5-.5H3z"/>
                                </svg>
                            </a>
                        </h4>
                        <ul class="list-unstyled">
                            <li>Created at: <strong>{{$order->created_at}}</strong></li>
                            <li>Payed_at: <strong>{{$order->payed_at}}</strong></li>
                            <li>Status: <strong>{{$order->status}}</strong></li>
                            <li>Bonus: <strong>{{$user->bonus}}</strong></li>
                        </ul>

                        <h4>Products</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td>#</td>
                                    <td>Product</td>
                                    <td>Options</td>
                                    <td>Calculator</td>
                                    <td>Price</td>
                                </tr>
                                @foreach($order->products as $k=>$product)
                                    <tr>
                                        <td>{{$k+1}}</td>
                                        <td>
                                            <a href="{{url($product->url)}}">{{$product->title}} </a>
                                            <span class="float-right"> {{$product->price}} <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                                                                                xmlns="http://www.w3.org/2000/svg">
                            <g id="euro_symbol_24px">
                                <path id="icon/action/euro_symbol_24px"
                                      d="M15 18.5C12.49 18.5 10.32 17.08 9.24 15H15V13H8.58C8.53 12.67 8.5 12.34 8.5 12C8.5 11.66 8.53 11.33 8.58 11H15V9H9.24C10.32 6.92 12.5 5.5 15 5.5C16.61 5.5 18.09 6.09 19.23 7.07L21 5.3C19.41 3.87 17.3 3 15 3C11.08 3 7.76 5.51 6.52 9H3V11H6.06C6.02 11.33 6 11.66 6 12C6 12.34 6.02 12.67 6.06 13H3V15H6.52C7.76 18.49 11.08 21 15 21C17.31 21 19.41 20.13 21 18.7L19.22 16.93C18.09 17.91 16.62 18.5 15 18.5Z"
                                      fill="#212529" fill-opacity="1"/>
                            </g>
                        </svg></span>
                                        </td>
                                        <td>
                                            @if($product->selected_options)
                                                <ol>
                                                    @foreach($product->selected_options as $option)
                                                        <li>
                                                            {{$option->title}}
                                                            <span class="float-right">
                                                                {{$option->price}}
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
                                                            </span>
                                                            <div style="clear: both"></div>
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            @endif
                                        </td>

                                        <td>
                                            @if($product->pivot->range)
                                                @php $range = json_decode($product->pivot->range) @endphp
                                                <p class="m-0"><strong>{{$product->calculator->name}}</strong></p>
                                                <p>{{$product->calculator->min_title}}: <strong>{{$range->from}}</strong> - {{$product->calculator->max_title}}: <strong>{{$range->to}}</strong></p>
                                                <hr>
                                                <p>
                                                    {{$product->calculator->calc($range)}}
                                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <g id="euro_symbol_24px">
                                                            <path id="icon/action/euro_symbol_24px"
                                                                  d="M15 18.5C12.49 18.5 10.32 17.08 9.24 15H15V13H8.58C8.53 12.67 8.5 12.34 8.5 12C8.5 11.66 8.53 11.33 8.58 11H15V9H9.24C10.32 6.92 12.5 5.5 15 5.5C16.61 5.5 18.09 6.09 19.23 7.07L21 5.3C19.41 3.87 17.3 3 15 3C11.08 3 7.76 5.51 6.52 9H3V11H6.06C6.02 11.33 6 11.66 6 12C6 12.34 6.02 12.67 6.06 13H3V15H6.52C7.76 18.49 11.08 21 15 21C17.31 21 19.41 20.13 21 18.7L19.22 16.93C18.09 17.91 16.62 18.5 15 18.5Z"
                                                                  fill="#212529" fill-opacity="1"/>
                                                        </g>
                                                    </svg>
                                                </p>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            {{$product->final_price}}
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g id="euro_symbol_24px">
                                                    <path id="icon/action/euro_symbol_24px"
                                                          d="M15 18.5C12.49 18.5 10.32 17.08 9.24 15H15V13H8.58C8.53 12.67 8.5 12.34 8.5 12C8.5 11.66 8.53 11.33 8.58 11H15V9H9.24C10.32 6.92 12.5 5.5 15 5.5C16.61 5.5 18.09 6.09 19.23 7.07L21 5.3C19.41 3.87 17.3 3 15 3C11.08 3 7.76 5.51 6.52 9H3V11H6.06C6.02 11.33 6 11.66 6 12C6 12.34 6.02 12.67 6.06 13H3V15H6.52C7.76 18.49 11.08 21 15 21C17.31 21 19.41 20.13 21 18.7L19.22 16.93C18.09 17.91 16.62 18.5 15 18.5Z"
                                                          fill="#212529" fill-opacity="1"/>
                                                </g>
                                            </svg>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <p>
                            <strong>Final price: {{$order->amount}}
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="euro_symbol_24px">
                                        <path id="icon/action/euro_symbol_24px"
                                              d="M15 18.5C12.49 18.5 10.32 17.08 9.24 15H15V13H8.58C8.53 12.67 8.5 12.34 8.5 12C8.5 11.66 8.53 11.33 8.58 11H15V9H9.24C10.32 6.92 12.5 5.5 15 5.5C16.61 5.5 18.09 6.09 19.23 7.07L21 5.3C19.41 3.87 17.3 3 15 3C11.08 3 7.76 5.51 6.52 9H3V11H6.06C6.02 11.33 6 11.66 6 12C6 12.34 6.02 12.67 6.06 13H3V15H6.52C7.76 18.49 11.08 21 15 21C17.31 21 19.41 20.13 21 18.7L19.22 16.93C18.09 17.91 16.62 18.5 15 18.5Z"
                                              fill="#212529" fill-opacity="1"/>
                                    </g>
                                </svg>
                            </strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
