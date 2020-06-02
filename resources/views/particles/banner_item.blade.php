<div class="preview">
    <div class="custom-bg d-flex justify-content-center" style="background-image: url({{$banner->background}})">
        <img class="custom-bg-image" src="{{$banner->object_image}}">
        <div class="custom-bg-content d-none d-md-flex">
            <div class="text-white">{!! $banner->text !!}</div>
            <a href="{{$banner->action_url}}" class="btn btn-danger mt-3">{{$banner->action_title}}</a>
        </div>
    </div>
    <div class="d-md-none bg-dark text-center p-3">
        <div class="text-white">{!! $banner->text !!}</div>
        <a href="{{$banner->action_url}}" class="btn btn-danger mt-3">{{$banner->action_title}}</a>
    </div>
</div>
