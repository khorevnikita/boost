@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Update SEO info
                    </div>

                    <div class="card-body">
                        <form action="{{url("/admin/seo")}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="title">Site title</label>
                                <input id="title" type="text" class="form-control" name="title" value="{{$seo->title??""}}">
                            </div>
                            <div class="form-group">
                                <label for="description">Site description</label>
                                <textarea id="description" type="text" class="form-control" rows="3" name="description">{{$seo->description??""}}</textarea>
                            </div>

                            <div class="form-group">
                                @if($seo && $seo->image)
                                    <img src="{{Storage::disk("public")->url($seo->image)}}" class="img-fluid" style="max-width: 150px">
                                    <div class="mt-2"></div>
                                @endif
                                <label for="image">Site image</label>
                                <input id="image" type="file" name="image">
                            </div>
                            <div class="form-group">
                                <label for="keys">Keys</label>
                                <textarea id="keys" name="keys" class="form-control" rows="4">{{$seo->keys??""}}</textarea>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
