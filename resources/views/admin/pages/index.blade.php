@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Pages
                    </div>

                    <div class="card-body">
                        <ul>
                            @foreach($pages as $page)
                                <li>
                                    <a href="{{url("admin/pages/$page->id/edit")}}">{{$page->name}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
