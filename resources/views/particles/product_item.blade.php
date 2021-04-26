<a href="/{{$product->url}}">
    <div class="card product-item-card">
        <div class="card-body text-center">
            <span class="product-item-price">
                {{$product->price}} {!! $currency=="usd"?"$":"&euro;" !!}
            </span>
            <div class="position-absolute d-flex flex-column">
                @if($product->category)
                    <span class="badge badge-secondary" style="background: linear-gradient(233.78deg, {{$product->category->color_from?:"#FF7A00"}} 10.37%, rgba(255, 255, 255, 0) 71.14%), {{$product->category->color_to?:"#A6A020"}};">{{$product->category->title}}</span>
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
        <div class="card-footer text-white text-center" style="    margin-top: -20px;">
            {{$product->title}}
        </div>
    </div>
</a>
