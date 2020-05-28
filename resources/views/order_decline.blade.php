@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 mt-5">
                <div class="alert alert-danger">
                    Payment declined. You may see more information about payment details on your <a href="{{url("home")}}">home page</a> or try again later.
                </div>
            </div>
        </div>
    </div>
@endsection
