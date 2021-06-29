@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        {{$page->name}}
                    </div>

                    <div class="card-body" id="app">
                        <form action="{{url("/admin/pages/$page->id")}}" method="post" class="mt-3">
                            @csrf
                            @method("PUT")
                            <div class="form-group">
                                <label for="text">Text</label>
                                <textarea id="text" name="text">{{$page->text}}</textarea>
                                @error('text')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            @if($page->key=="main")
                                <p><strong>Products</strong></p>
                                @foreach($games as $game)
                                    <div class="form-group">
                                        <label for="#game{{$game->id}}">{{$game->title}}</label>
                                        <input id="game{{$game->id}}" class="form-control" placeholder="Choose products">
                                        <p class="mt-1" id="selected-crosses-{{$game->id}}">
                                            <span class="d-none badge badge-info text-white p-2 mr-2 mt-1 example-badge">
                                                <a style="cursor: pointer" class="text-danger js-remove-item">x</a>
                                                <input type="hidden" name="products[]">
                                            </span>
                                            @foreach($game->main_products as $product)
                                                <span class="badge badge-info text-white p-2 mr-2 mt-1">
                                                    {{$product->title}}&nbsp;
                                                    <a style="cursor: pointer" class=" text-danger js-remove-item">x</a>
                                                    <input type="hidden" name="products[]" value="{{$product->id}}">
                                                </span>
                                            @endforeach
                                        </p>
                                    </div>
                                @endforeach
                            @endif
                            <div class="form-group">
                                <button type="submit" class="btn btn-dark">Save page</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push("scripts")
        <script>

            document.addEventListener("DOMContentLoaded", function () {
                $('#text').summernote({
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

            @foreach($games as $game)
            $("#game{{$game->id}}").autocomplete({
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
                            game_id: "{{$game->id}}"
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
                    let item = $("#selected-crosses-{{$game->id}}").find(".example-badge").clone(true, true).removeClass("example-badge");
                    item.removeClass("d-none")
                    item.html(ui.item.label + item.html());
                    item.find("input").val(ui.item.value);
                    $("#selected-crosses-{{$game->id}}").append(item);
                    $(".js-remove-item").click(function () {
                        $(this).parent().remove()
                    });
                    setTimeout(() => {
                        $("#game{{$game->id}}").val("");
                    }, 50);
                }
            });
            @endforeach
            $(".js-remove-item").click(function () {
                $(this).parent().remove()
            });
        </script>
    @endpush
@endsection
