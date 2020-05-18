@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <a href="{{url("/admin/games/create")}}" class="btn btn-primary float-right">
                            Add a game
                        </a>
                        Games list
                    </div>

                    <div class="card-body">
                        @if($games->count()>0)
                            <ul>
                                @foreach($games as $game)
                                    <li>
                                        <a href="{{url("/admin/games/$game->id/edit")}}">{{$game->title}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="alert alert-primary">No games found. Create the first</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
