<a href="/{{$product->url}}">
    <div class="card product-item-card">
        <div class="card-body text-center">
            <span class="product-item-price">
                {{$product->price}} {!! $currency=="usd"?"$":"&euro;" !!}
            </span>
            <div class="position-absolute d-flex flex-column">
                @if($product->category)
                    <span class="badge badge-secondary">{{$product->category->title}}</span>
                @endif
                @if($product->is_hot)
                    <span class="badge badge-danger">HOT</span>
                @endif
                @if($product->is_new)
                    <span class="badge badge-danger">NEW</span>
                @endif
            </div>
            <img src="{{$product->banner}}" class="img-fluid">
        </div>
        <div class="card-footer text-white text-center">
            {{$product->title}}
        </div>
    </div>
</a>
