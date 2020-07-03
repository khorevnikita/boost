@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <a href="{{url("/admin/options/create")}}" class="btn btn-primary float-right">Add an option</a>
                        Options list
                    </div>

                    <div class="card-body">
                        <form action="{{url("admin/options")}}" class="input-group mb-3">
                            <input placeholder="Search.." type="text" class="form-control" name="title" value="{{Request::input(("title"))}}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g id="search_24px">
                                            <path id="icon/action/search_24px" fill-rule="evenodd" clip-rule="evenodd"
                                                  d="M14.965 14.255H15.755L20.745 19.255L19.255 20.745L14.255 15.755V14.965L13.985 14.685C12.845 15.665 11.365 16.255 9.755 16.255C6.16504 16.255 3.255 13.345 3.255 9.755C3.255 6.16501 6.16504 3.255 9.755 3.255C13.345 3.255 16.255 6.16501 16.255 9.755C16.255 11.365 15.665 12.845 14.6851 13.985L14.965 14.255ZM5.255 9.755C5.255 12.245 7.26501 14.255 9.755 14.255C12.245 14.255 14.255 12.245 14.255 9.755C14.255 7.26501 12.245 5.255 9.755 5.255C7.26501 5.255 5.255 7.26501 5.255 9.755Z"
                                                  fill="white" fill-opacity="1"/>
                                        </g>
                                    </svg>
                                </button>
                            </div>
                        </form>
                        @if($options->count()>0)
                            <ul class="list-group ">
                                @foreach($options as $option)
                                    <li class="list-group-item list-group-item-action">
                                        <a href="{{url("/admin/options/$option->id/edit")}}">
                                            #{{$option->id}} {{$option->title}} [ {{$option->price}} @if($option->type=="percent") % @endif ]
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="mt-3">
                                {{$options->withQueryString()->links()}}
                            </div>
                        @else
                            <div class="alert alert-primary">No options found. Create the first</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
