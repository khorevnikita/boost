@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Order #{{$order->id}}
                    </div>

                    <div class="card-body">
                        {{--}}<div class="row">
                            <div class="col-6">
                                <div class="list-group" id="list-tab" role="tablist">
                                    @foreach($order->products as $k=>$product)
                                        <a class="list-group-item list-group-item-action @if(!$k) active @endif" id="list-home-list" data-toggle="list" href="#list-{{$k}}"
                                           role="tab" aria-controls="home">
                                            {{$product->title}}
                                            <span class="float-right">
                                            {{$product->price}} {{$product->currency}}

                                            <button data-type="product" data-product="{{$product->id}}" type="button"
                                                    class="btn btn-link text-danger js-remove-item-from-order-admin">
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
                                                            <span class="float-right">{{$option->price}} {{$option->type=="abs"?$product->currency:"%"}}
                                                        <button data-type="option" data-product="{{$product->id}}" data-option="{{$option->id}}" type="button"
                                                                class="btn btn-link text-danger js-remove-item-from-order-admin">
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
                        <p><strong>Final price: {{$order->amount}}</strong></p>--}}
                        <form action="{{url("/admin/orders/$order->id")}}" method="post" class="mt-3">
                            @csrf
                            @method("PUT")
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select id="status" name="status" class="form-control">
                                    <option @if($order->status=="new") selected @endif value="new">New</option>
                                    <option @if($order->status=="formed") selected @endif value="formed">Formed</option>
                                    <option @if($order->status=="payed") selected @endif value="payed">Payed</option>
                                    <option @if($order->status=="done") selected @endif value="done">Done</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="comment">Comment</label>
                                <textarea class="form-control" rows="2" id="comment" name="comment"></textarea>
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
    @push("scripts")
        <script>
            $(".js-remove-item-from-order-admin").click(function () {
                axios.post("/admin/orders/{{$order->id}}", $(this).data()).then(r => {
                    if (r.data.status === "success") {
                        window.location.reload();
                    }
                })
            })
        </script>
    @endpush
@endsection
