@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Main banner
                        <a href="{{url("admin/banners/create")}}" class="float-right">Create a banner</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <td>Place</td>
                                    <td>Preview</td>
                                    <td>Published</td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($banners as $banner)
                                    <tr>
                                        <td style="vertical-align: middle;">{{$banner->game?$banner->game->title:"Main banner"}}</td>
                                        <td>
                                            @include('particles.banner_item', ['banner' => $banner])
                                        </td>
                                        <td style="vertical-align: middle;">
                                            @if($banner->published)
                                                Yes
                                            @else
                                                No
                                            @endif
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <a href="{{url("admin/banners/$banner->id/edit")}}">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
