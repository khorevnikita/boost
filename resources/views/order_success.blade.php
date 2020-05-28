@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 mt-5">
                <div class="alert alert-success">
                    Payment proceeded. You may see more information about payment details on your <a href="{{url("home")}}">home page</a>
                </div>
            </div>
        </div>
    </div>
@endsection
