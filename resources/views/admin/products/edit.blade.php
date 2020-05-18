@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{$product->title}}
                    </div>

                    <div class="card-body" id="app">
                        <h4>General information</h4>
                        <form action="{{url("/admin/products/$product->id")}}" method="post" class="mt-3">
                            @csrf
                            @method("PUT")
                            <div class="form-group">
                                <label for="title">Product title</label>
                                <input id="title" type="text" class="form-control" name="title" value="{{$product->title}}">
                            </div>
                            {{--<div class="form-group">
                                <label for="short_description">Short description</label>
                                <textarea id="short_description" type="text" class="form-control" name="short_description">{{$product->short_description}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" type="text" class="form-control" name="description">{{$product->description}}</textarea>
                            </div>
                            --}}
                            <div class="form-group">
                                <label for="short_description">Short description</label>
                                <textarea id="short_description" name="short_description">{{$product->short_description}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description">{{$product->description}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input id="price" type="text" class="form-control" name="price" value="{{$product->price}}">
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input value="1" @if($product->is_hot) checked @endif type="checkbox" class="form-check-input" name="is_hot" id="is_hot">
                                    <label class="form-check-label" for="is_hot">Hot</label>
                                </div>
                                <div class="form-check">
                                    <input name="is_new" value="1" @if($product->is_new) checked @endif type="checkbox" class="form-check-input" id="is_new">
                                    <label class="form-check-label" for="is_new">New</label>
                                </div>
                            </div>

                            @php $productOpts = $product->options()->pluck("options.id")->toArray(); @endphp
                            @foreach($options as $opt)
                                <div class="form-check">
                                    <input name="options[]" value="{{$opt->id}}" @if(in_array($opt->id,$productOpts)) checked @endif type="checkbox" class="form-check-input"
                                           id="opt{{$opt->id}}">
                                    <label class="form-check-label" for="opt{{$opt->id}}">{{$opt->title}}</label>
                                </div>
                            @endforeach

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                        <hr>

                        <div class="form-group">
                            <form action="{{url("admin/products/$product->id")}}" method="post">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn btn-danger float-right">Delete product</button>
                            </form>
                        </div>
                        <div style="clear:both"></div>
                        <h4 class="mt-3">Attach images</h4>

                        @if($product->images->count()>0)
                            <div class="row">
                                @foreach($product->images as $image)
                                    <div class="col-12 col-sm-6 mt-3 text-center">
                                        <form action="{{url("/admin/images/$image->id")}}" method="post">
                                            @csrf
                                            @method("DELETE")
                                            <button class="btn btn-link text-danger float-right">X</button>
                                        </form>
                                        <img alt="no_img" src="{{$image->url}}" class="img-fluid" style="width: 150px;">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-primary">No images with product. Attach the first</div>
                        @endif
                        {{--<form action="{{url("/admin/images")}}" method="post" enctype="multipart/form-data" class="mt-3">
                            @csrf
                            <div class="form-group">
                                <label>Choose file to attach</label>
                                <input type="file" name="file">
                            </div>
                            <input type="hidden" name="product_id" value="{{$product->id}}">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Attach</button>
                            </div>
                        </form>--}}
                        <button class="btn btn-outline-primary mt-3" id="pick-avatar">Upload image</button>
                        <image-cropper
                            @uploaded="reload()"
                            trigger="#pick-avatar"
                            :labels="{submit:'Save',cancel:'Cancel'}"
                            :upload-form-data="{product_id:'{{$product->id}}',_token:'{{csrf_token()}}'}"
                            upload-url="/admin/images"
                            :output-options="{width:1400,height:300}"
                            :cropper-options="{aspectRatio: 16/9,autoCropArea: 1,viewMode: 1, movable: false, zoomable: false}"
                        ></image-cropper>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#short_description').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            $('#description').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
@endsection
