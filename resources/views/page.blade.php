@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row ">
            <div class="col-12 info-page" style="margin-top: 80px">
                {!! $page->text !!}
            </div>
        </div>
    </div>
@endsection
