@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <a href="{{url("/admin/options/create")}}" class="btn btn-primary float-right">
                            Add an option
                        </a>
                        Options list
                    </div>

                    <div class="card-body">
                        @if($options->count()>0)
                            <ul>
                                @foreach($options as $option)
                                    <li>
                                        <a href="{{url("/admin/options/$option->id/edit")}}">{{$option->title}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="alert alert-primary">No options found. Create the first</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
