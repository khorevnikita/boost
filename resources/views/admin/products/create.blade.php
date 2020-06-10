@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        Create a product
                    </div>

                    <div class="card-body">
                        <form action="{{url("/admin/products")}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="title">Product title</label>
                                <input id="title" type="text" class="form-control" name="title">
                                @error('title')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="short_description">Short description</label>
                                <textarea id="short_description" class="form-control" name="short_description" rows="3"></textarea>
                                @error('short_description')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" class="form-control" name="description" rows="3"></textarea>
                                @error('description')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input id="price" type="text" class="form-control" name="price">
                                @error('price')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select id="category_id" name="category_id" class="form-control">
                                    @foreach($categories as $category)
                                        <option @if(Request::has("category_id") && Request::input("category_id")==$category->id) selected
                                                @endif value="{{$category->id}}">
                                            {{$category->game->title}} - {{$category->title}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input value="1" checked type="checkbox" class="form-check-input" name="is_hot" id="is_hot">
                                    <label class="form-check-label" for="is_hot">Hot</label>
                                </div>
                                <div class="form-check">
                                    <input name="is_new" value="1" checked type="checkbox" class="form-check-input" id="is_new">
                                    <label class="form-check-label" for="is_new">New</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary">Save</button>
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
    @endpush
@endsection
