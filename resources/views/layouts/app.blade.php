<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app" class="container">
    @auth
        <form id="logout-form" action="{{url("logout")}}" method="post">
            @csrf
            <button type="submit" class="float-right mt-2 btn btn-link js-logout">
                <svg class="bi bi-x-circle-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="#343a40" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                          d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.146-3.146a.5.5 0 0 0-.708-.708L8 7.293 4.854 4.146a.5.5 0 1 0-.708.708L7.293 8l-3.147 3.146a.5.5 0 0 0 .708.708L8 8.707l3.146 3.147a.5.5 0 0 0 .708-.708L8.707 8l3.147-3.146z"/>
                </svg>
            </button>
        </form>
    @endauth
    <div class="row">
        <nav class="col-md-1 d-none d-md-block bg-dark sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{url("/")}}" class="nav-link">
                            <img class="img-fluid" src="/images/logo.png">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a role="button" class="nav-link js-menu-toggle">
                            <img class="img-fluid" src="/images/icons/menu.svg">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a role="button" class="nav-link">
                            <img src="/images/icons/search.svg" class="img-fluid">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url("/order")}}" role="button" class="nav-link">
                            <img src="/images/icons/shop.svg" class="img-fluid">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url("register")}}" class="nav-link">
                            <img src="/images/icons/auth.svg" class="img-fluid">
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="fixed-list" style="display: none">
            <nav class="col-md-3 offset-1 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Games:</strong></li>
                        @foreach($games as $game)
                            <li class="list-group-item">
                                <a href="{{url("/$game->id")}}" class="text-muted">
                                    <strong>{{$game->title}}</strong>
                                    <img src="/images/icons/left_arrow.svg" class="img-fluid">
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </nav>
        </div>
        <main class="py-4 col-md-11 ml-sm-auto col-lg-11 pt-3 px-4">
            @yield('content')
        </main>
    </div>
</div>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@stack("scripts")
</body>
</html>
