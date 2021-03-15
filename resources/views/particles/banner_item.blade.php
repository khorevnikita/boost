<div class="preview" style="background-image: url({{$banner->background}})">
    <div class="custom-bg d-flex justify-content-center">

        <div class="custom-bg-content d-md-flex">
            <div class="text-white banner-text">{!! $banner->text !!}</div>
            <a href="{{$banner->action_url}}" class="btn btn-primary b-r-30 mt-3">{{$banner->action_title}}</a>
        </div>
        <img class="custom-bg-image d-none d-sm-block" src="{{$banner->object_image}}">
    </div>
    {{--<div class="d-md-none bg-dark text-center p-3">
        <div class="text-white">{!! $banner->text !!}</div>
        <a href="{{$banner->action_url}}" class="btn btn-danger mt-3">{{$banner->action_title}}</a>
    </div>--}}
</div>
