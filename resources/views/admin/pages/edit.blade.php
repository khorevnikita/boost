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
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save product</button>
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
        </script>
    @endpush
@endsection
