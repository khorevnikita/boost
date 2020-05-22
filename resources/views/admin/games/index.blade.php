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
                        <p> Game list</p>
                    </div>

                    <div class="card-body">
                        @if($games->count()>0)
                            <ul class="list-group list-group-item-action ">
                                @foreach($games as $game)
                                    <li class="list-group-item">
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
