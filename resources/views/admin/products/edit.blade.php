@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        {{$product->title}}
                        @if($product->url)
                            <a class="float-right" href="{{url($product->url)}}">Open card</a>
                        @endif
                    </div>

                    <div class="card-body" id="app">
                        <h4>General information</h4>
                        <form action="{{url("/admin/products/$product->id")}}" method="post" class="mt-3">
                            @csrf
                            @method("PUT")
                            <div class="form-group">
                                <label for="title">Product title</label>
                                <input id="title" type="text" class="form-control" name="title" value="{{$product->title}}">
                                @error('title')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="rewrite">Rewrite name</label>
                                <input id="rewrite" type="text" class="form-control" name="rewrite" value="{{$product->rewrite}}">
                                @error('rewrite')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="short_description">Short description</label>
                                <textarea id="short_description" name="short_description">{{$product->short_description}}</textarea>
                                @error('short_description')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description">{{$product->description}}</textarea>
                                @error('description')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input id="price" type="text" class="form-control" name="price" value="{{$product->price}}">
                                @error('price')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
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
                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select id="category_id" name="category_id" class="form-control">
                                    @foreach($categories as $category)
                                        <option @if($product->category_id == $category->id) selected @endif value="{{$category->id}}">
                                            {{$category->game->title}} - {{$category->title}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <hr>
                            @php $productOpts = $product->options()->get(); @endphp
                            <div class="form-group">
                                <label for="options">Options</label>
                                <input id="options" class="form-control" placeholder="Choose options">
                                <p class="mt-1" id="selected-options">
                                    <span class="badge badge-info text-white p-2 mr-2 mt-1 example-badge">
                                        <a style="cursor: pointer" class="text-danger js-remove-item">x</a>
                                            <input type="hidden" name="options[]">
                                    </span>
                                    @foreach($productOpts as $id=>$opt)
                                        <span class="badge badge-info text-white p-2 mr-2 mt-1">
                                            #{{$opt->id}} {{$opt->title}} [ {{$opt->price}} @if($opt->type=="percent") % @endif ]&nbsp;
                                            <a style="cursor: pointer" class="text-danger js-remove-item">x</a>
                                            <input type="hidden" name="options[]" value="{{$opt->id}}">
                                        </span>
                                    @endforeach
                                </p>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="crosses">Crosses</label>
                                <input id="crosses" class="form-control" placeholder="Choose crosses">
                                <p class="mt-1" id="selected-crosses">
                                    <span class="badge badge-info text-white p-2 mr-2 mt-1 example-badge">
                                        <a style="cursor: pointer" class="text-danger js-remove-item">x</a>
                                            <input type="hidden" name="crosses[]">
                                    </span>
                                    @foreach($product->crosses as $id=>$cross)
                                        <span class="badge badge-info text-white p-2 mr-2 mt-1">
                                            {{$cross->title}}&nbsp;
                                            <a style="cursor: pointer" class="text-danger js-remove-item">x</a>
                                            <input type="hidden" name="crosses[]" value="{{$cross->id}}">
                                        </span>
                                    @endforeach
                                </p>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save product</button>
                            </div>
                        </form>
                        <hr>
                        <h4>Calculator</h4>
                        <calculator-editor @if($calculator) :calculator="{{$calculator}}" @endif :product="{{$product}}"></calculator-editor>
                        @if($calculator)
                            <div class="form-group float-right">
                                <form action="{{url(" admin/calculators/$calculator->id")}}" method="post">
                                    @csrf
                                    @method("DELETE")
                                    <button onclick="return confirm('Are you sure you want to delete CALCULATOR?')" type="submit" class="btn btn-danger float-right">Delete
                                        calculator
                                    </button>
                                </form>
                            </div>
                            <div style="clear:both"></div>
                        @endif
                        <hr>

                        <div class="form-group">
                            <form action="{{url("admin/products/$product->id")}}" method="post">
                                @csrf
                                @method("DELETE")
                                <button onclick="return confirm('Are you sure you want to delete PRODUCT?')" type="submit" class="btn btn-danger float-right">Delete product
                                </button>
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
    @push("scripts")
        <script>
            $(".js-delete-step").click(function () {
                $(this).parent().parent().remove()
            });
            $(".js-add-step").click(function () {
                let rowItem = $(".step-row");
                let clone = rowItem.clone();
                rowItem.parent().append(clone)
                $(".js-delete-step").click(function () {
                    $(this).parent().parent().remove()
                });
            });

            document.addEventListener("DOMContentLoaded", function () {
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

                $(".js-remove-item").click(function () {
                    $(this).parent().remove()
                });
                $("#options").autocomplete({
                    source: function (request, response) {
                        // организуем кроссдоменный запрос
                        $.ajax({
                            url: "/api/options",
                            //  dataType: "jsonp",
                            // параметры запроса, передаваемые на сервер (последний - подстрока для поиска):
                            data: {
                                q: request.term
                            },
                            // обработка успешного выполнения запроса
                            success: function (data) {
                                console.log(data);
                                response($.map(data.options, function (item) {
                                    return {
                                        label: "#" + item.id + " " + item.title + "[ " + item.price + (item.type === 'percent' ? '%' : '') + "]",
                                        value: item.id
                                    }
                                }));

                            }
                        });
                    },
                    select: function (event, ui) {
                        let item = $("#selected-options").find(".example-badge").clone(true, true).removeClass("example-badge");
                        item.html(ui.item.label + item.html());
                        item.find("input").val(ui.item.value);
                        $("#selected-options").append(item);
                        $("#options").val("");
                        $(".js-remove-item").click(function () {
                            $(this).parent().remove()
                        })
                    }
                });

                $("#crosses").autocomplete({
                    source: function (request, response) {
                        // организуем кроссдоменный запрос
                        $.ajax({
                            url: "/api/products",
                            //  dataType: "jsonp",
                            // параметры запроса, передаваемые на сервер (последний - подстрока для поиска):
                            data: {
                                featureClass: "P",
                                style: "full",
                                maxRows: 12,
                                q: request.term,
                                game_id: "{{$product->category->game_id}}"
                            },
                            open: function () {
                                $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
                            },
                            close: function () {
                                $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
                            },
                            // обработка успешного выполнения запроса
                            success: function (data) {
                                console.log(data);
                                //return data.products;
                                // приведем полученные данные к необходимому формату и передадим в предоставленную функцию response
                                response($.map(data.products, function (item) {
                                    return {
                                        label: item.title,
                                        value: item.id
                                    }
                                }));
                            }
                        });
                    },
                    select: function (event, ui) {
                        let item = $("#selected-crosses").find(".example-badge").clone(true, true).removeClass("example-badge");
                        item.html(ui.item.label + item.html());
                        item.find("input").val(ui.item.value);
                        $("#selected-crosses").append(item);
                        $("#crosses").val("");
                        $(".js-remove-item").click(function () {
                            $(this).parent().remove()
                        })
                    }
                });
            });
        </script>
    @endpush
@endsection
